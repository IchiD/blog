<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Topic;

class PagesController extends Controller
{
    public function index() {
        // getでindexにアクセスされた場合の処理

        $topics  = Topic::latest()->get();

        return view('index' , [ "topics" => $topics ]); // view関数の第二引数に連想配列を渡す
    }   
    public function save(Request $request) {
        // postでsaveにアクセスされた場合の処理

        //Topicモデルのインスタンスを作成
        $topic = new Topic();

        //nameとcontentが指定されている場合保存する(バリデーションを行うならここ？)
        if ($request->content){
            $topic->name = auth()->user()->name ?? '名無し';
            $topic->title = $request->title ?? 'タイトル無し';
            $topic->content = $request->content;
            $topic->save();
        }
        return redirect('/');
    }   
}