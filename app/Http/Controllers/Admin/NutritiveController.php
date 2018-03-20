<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\NutritiveRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\NutritiveRepositoryEloquent  as NutritiveRepository;

class NutritiveController extends Controller
{
    public $Nutritive;
    public $role;
    public $nutritive_type = [
        ['id'=>'1','display_name'=>'内补'],
        ['id'=>'2','display_name'=>'外补'],
        ['id'=>'3','display_name'=>'维生素'],
        ['id'=>'4','display_name'=>'微量元素'],
    ];
    public function __construct(NutritiveRepository $NutritiveRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:nutritive');
        $this->Nutritive = $NutritiveRepository;
        $this->role = $roleRepository;
    }

    /**
     * 列表页
     */
    public function index()
    {
        $role = auth('admin')->user()->roles->toArray()[0]['display_name'];
        return view('admin.nutritive.index');
    }
    /*
     * 新增页面
     */
    public function create()
    {
        $nutritive_type = $this->nutritive_type;
        return view('admin.nutritive.create',compact('nutritive_type'));
    }
    /*
     * 新增入库
     */
    public function store(NutritiveRequest $request)
    {
        $this->Nutritive->create($request->all());
        return redirect('admin/nutritive');
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $data= $this->Nutritive->editViewData($id);
        $nutritive_type = $this->nutritive_type;
        return view('admin.nutritive.edit',compact('data','nutritive_type'));
    }
    /*
     * 编辑入库
     */
    public function update(NutritiveRequest $request, $id)
    {
        $this->Nutritive->updateNutritive($request->all(),$id);
        return redirect('admin/nutritive');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->Nutritive->deleteNutritive($id);
        return redirect('admin/nutritive');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->Nutritive->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
