<?php

namespace App\Http\Controllers\Admin;

use App\History;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Work;
use Carbon\Carbon;

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

    public function edit(Request $request)
    {   
        //Work Modelからデータを取得
        $work = Work::find($request->id);
        if (empty($work)) {
            abort(404);
        }
        return view('admin.work.edit', ['work_form' => $work]);
    }

    public function update(Request $request)
    {
        //Validationをかける
        $this->validate($request, Work::$rules);
        //Work Modelからデータを取得
        $work = Work::find($request->id);
        //送信されてきたフォームデータを格納
        $work_form = $request->all();
        if (isset($work_form['file'])) {
            $path = $request->file('file')->store('');
            $work->file = basename($path);
            unset($work_form['file']);
        } elseif (0 == strcmp($request->remove, 'true')) {
            $work->file = null;
        }
        unset($work_form['_token']);
        unset($work_form['file']);
        unset($work_form['remove']);

        //該当するデータを上書きして保存
        $work->fill($work_form)->save();

        $histories = new History;
        $histories->work_id = $work->id;
        $histories->edited_at = Carbon::now();
        $histories->save();

        return redirect('admin/work/');
    }

    public function delete(Request $request)
    {
        $work = Work::find($request->id);
        //削除する
        $work->delete();
        return redirect('admin/work/');
    }
}
