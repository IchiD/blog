<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;
use Intervention\Image\Facades\Image;


class PagesController extends Controller
{
    public function index()
    {
        // getでindexにアクセスされた場合の処理
        $topics  = Topic::latest()->get();

        return view('index', ["topics" => $topics]); // view関数の第二引数に連想配列を渡す
    }
    public function save(Request $request)
    {
        // postでsaveにアクセスされた場合の処理

        //Topicモデルのインスタンスを作成
        $topic = new Topic();

        //nameとcontentが指定されている場合保存する(バリデーションを行うならここ？)
        if ($request->content) {
            $topic->name = auth()->user()->name ?? '名無し';
            $topic->title = $request->title ?? 'タイトル無し';
            $topic->content = $request->content;
            $topic->user_id = auth()->user()->id ?? null;

            if ($request->hasFile('imgpath')) {
                $image = $request->file('imgpath');
                $filename = time() . $image->getClientOriginalName();
                // 画像をリサイズして保存
                $thumbnailPath = public_path('thumbnails/' . $filename);
                Image::make($image->getRealPath())->resize(200, 200)->save($thumbnailPath);

                // オリジナル画像を保存
                $imagePath = public_path('images/' . $filename);
                Image::make($image->getRealPath())->save($imagePath);

                $topic->imgpath = $filename;
            }

            $topic->save();
            return redirect('/')->with('success', '投稿しました！');
        } else {
            return redirect('/')->with('error', '本文を入力してください。');
        }
    }

    public function destroy($id)
    {
        $topic = Topic::find($id);
        if (auth()->id() !== $topic->user_id) {
            return redirect('/')->with('error', 'この投稿は削除できません。');
        }
        // ログイン中のユーザーが投稿の所有者だった場合投稿を削除
        $topic->delete();
        return redirect('/')->with('success', '投稿を削除しました。');
    }
}
