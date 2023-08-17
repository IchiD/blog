<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;


class PagesController extends Controller
{
    public function index()
    {
        // getでindexにアクセスされた場合の処理
        $topics  = Topic::latest()->get();
        return view('topics.index', ["topics" => $topics]); // view関数の第二引数に連想配列を渡す
    }
    public function save(Request $request)
    {
        if (!auth()->check()) {
            return redirect('/')->with('error', 'ログインしてください。');
        }
        //Topicモデルのインスタンスを作成
        $topic = new Topic();

        //nameとcontentが指定されている場合保存する
        if ($request->content) {
            $topic->name = auth()->user()->name;
            $topic->title = $request->title ?? 'タイトル無し';
            $topic->content = $request->content;
            $topic->user_id = auth()->user()->id;

            if ($request->hasFile('img')) {
                $image = $request->file('img');
                $filename = time() . $image->getClientOriginalName();
                // 画像をリサイズして保存
                $thumbnailPath = storage_path('app/public/thumbnails/' . $filename);
                Image::make($image->getRealPath())
                    ->crop(250, 250)
                    ->resize(150, 150)
                    ->save($thumbnailPath);
                // オリジナル画像を保存
                $imagePath = storage_path('app/public/images/' . $filename);
                Image::make($image->getRealPath())->save($imagePath);

                $topic->imgpath = $filename;
            }

            $topic->save();
            return redirect('/')->with('success', '投稿しました！');
        } else {
            return redirect('/')->with('error', '本文を入力してください。');
        }
    }

    public function detail($id)
    {
        $topic = Topic::find($id);
        return view('topics.detail', ['topic' => $topic]);
    }

    public function destroy($id)
    {
        $topic = Topic::find($id);
        if (!$topic) {
            return redirect('/')->with('error', '投稿が見つかりません。');
        }
        // ログイン中のユーザーのIDと投稿のユーザーIDを比較
        if (auth()->id() !== $topic->user_id) {
            return redirect('/')->with('error', 'この投稿は削除できません。');
        }
        // 投稿を削除
        $topic->delete();
        // 画像を削除
        Storage::disk('public')->delete('images/' . $topic->imgpath);
        Storage::disk('public')->delete('thumbnails/' . $topic->imgpath);
        return redirect('/')->with('success', '投稿を削除しました。');
    }

    public function edit($id)
    {
        $topic = Topic::find($id);
        if (auth()->id() !== $topic->user_id) {
            return redirect('/')->with('error', 'この投稿は編集できません。');
        }
        return view('topics.edit', ['topic' => $topic]);
    }

    public function update(Request $request, $id)
    {
        $topic = Topic::find($id);
        if (auth()->id() !== $topic->user_id) {
            return redirect('/')->with('error', 'この投稿は編集できません。');
        }
        $topic->title = $request->title;
        $topic->content = $request->content;

        if ($request->hasFile('img')) {
            $image = $request->file('img');
            $filename = time() . $image->getClientOriginalName();
            // 画像をリサイズして保存
            $thumbnailPath = storage_path('app/public/thumbnails/' . $filename);
            Image::make($image->getRealPath())
                ->crop(250, 250)
                ->resize(150, 150)
                ->save($thumbnailPath);
            // オリジナル画像を保存
            $imagePath = storage_path('app/public/images/' . $filename);
            Image::make($image->getRealPath())->save($imagePath);

            $topic->imgpath = $filename;
        }

        $topic->save();
        return redirect('/')->with('success', '投稿を更新しました。');
    }

    public function like(Topic $topic)
    {
        // dd($topic->id);
        // ユーザーがすでに「いいね」しているか確認
        $liked = $topic->likes()->where('user_id', auth()->id())->exists();

        if ($liked) {
            // 「いいね」を取り消す
            $topic->likes()->where('user_id', auth()->id())->delete();
        } else {
            // 「いいね」を追加する
            $topic->likes()->create(['user_id' => auth()->id()]);
        }

        return response()->json(['liked' => !$liked]);
        // return redirect()->back();
    }
}
