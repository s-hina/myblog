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
            $work->file_path = basename($path);
        } else {
            $work->file_path = null;
        }

    // フォームから送信されてきた_tokenを削除する
    unset($form['_token']);
    // フォームから送信されてきたimageを削除する
    unset($form['image']);
    
    $work->fill($form);
    $work->save();

        return redirect('admin/work/create');
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
