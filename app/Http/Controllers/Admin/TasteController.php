<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\TasteRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\TasteRepositoryEloquent  as TasteRepository;

class TasteController extends Controller
{
    public $Taste;
    public $role;
    public function __construct(TasteRepository $TasteRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:taste');
        $this->Taste = $TasteRepository;
        $this->role = $roleRepository;
    }

    /**
     * 列表页
     */
    public function index()
    {
        $role = auth('admin')->user()->roles->toArray()[0]['display_name'];
        return view('admin.taste.index');
    }
    /*
     * 新增页面
     */
    public function create()
    {
        return view('admin.taste.create');
    }
    /*
     * 新增入库
     */
    public function store(TasteRequest $request)
    {
        $this->Taste->create($request->all());
        return redirect('admin/taste');
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $data= $this->Taste->editViewData($id);

        return view('admin.taste.edit',compact('data'));
    }
    /*
     * 编辑入库
     */
    public function update(TasteRequest $request, $id)
    {
        $this->Taste->updateTaste($request->all(),$id);
        return redirect('admin/taste');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->Taste->deleteTaste($id);
        return redirect('admin/taste');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->Taste->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
