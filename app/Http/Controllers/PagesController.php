<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;

class PagesController extends Controller
{
    public function index() {

        $topics  = Topic::latest()->get();

        return view('index' , [ "topics" => $topics ]); 
    }   
    public function save(Request $request) {

        //Topicを受け入れるための箱を作る
        $topic = new Topic();

        //nameとcontentが指定されている場合保存する
        if ($request->name && $request->content){
            $topic->name = $request->name;
            $topic->content = $request->content;
            $topic->save();
        }

        return redirect('/');
    }   
}