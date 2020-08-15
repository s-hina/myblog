<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Work;

class WorkController extends Controller
{
    public function index(Request $request)
    {
        $cond_name = $request->cond_name;
        //$cond_nameが空白でない場合は、記事を検索して取得する
        if ($cond_name != '') {
            $posts = Work::where('name', $cond_name).orderBy('updated_at', 'desc')->get();
        } else {
            $posts = Work::all()->sortByDesc('updated_at');
        }

        if (count($posts) > 0) {
            $headline = $posts->shift();
        } else {
            $headline = null;
        }

        //work/index.blade.phpファイルを渡している
        //またViewテンプレートにheadline, posts, cond_nameという変数を渡している
        return view('work.index', ['headline' => $headline, 'posts' => $posts, "cond_name" => $cond_name]);
    }
}

