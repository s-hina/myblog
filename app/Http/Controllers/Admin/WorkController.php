<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Work;

class WorkController extends Controller
{
    public function add()
    {
        return view('admin.work.create');
    }

    public function create(Request $request)
    {
        $this->validate($request, Work::$rules);

        $work = new Work;
        $form = $request->all();

        if (isset($form['file'])) {
            $path = $request->file('file')->store('');
            $work->file = basename($path);
        } else {
            $work->file = null;
        }

    // フォームから送信されてきた_tokenを削除する
    unset($form['_token']);
    // フォームから送信されてきたimageを削除する
    unset($form['image']);
    
    $work->fill($form);
    $work->save();

        return redirect('admin/work/create');
    }

    public function index(Request $request)
    {
        $cond_name = $request->cond_name;
        if ($cond_name != '') {
            //検索されたら検索結果を取得
            $posts = Work::where('name', $cond_name)->get();
        } else {
            //それ以外はすべてのニュースを取得
            $posts = Work::all();
        }
        return view('admin.work.index', ['posts' => $posts, 'cond_name' => $cond_name]);
    }

    public function edit()
    {
        return view('admin.work.edit');
    }

    public function updata()
    {
        return redirect('admin/work/edit');
    }
}
