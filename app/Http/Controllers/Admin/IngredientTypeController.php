<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use App\Repositories\Eloquent\NutritiveRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\IngredientTypeRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\IngredientTypeRepositoryEloquent  as IngredientTypeRepository;

class IngredientTypeController extends Controller
{
    public $IngredientType;
    public $role;
    public function __construct(IngredientTypeRepository $IngredientTypeRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:ingredienttype');
        $this->IngredientType = $IngredientTypeRepository;
        $this->role = $roleRepository;
    }

    /**
     * 列表页
     */
    public function index()
    {
        $field = ['id','name','pid','cTime'];
        $ingredienttype = $this->IngredientType->getAll($field);
        return view('admin.ingredienttype.index',compact('ingredienttype'));
    }
    /*
     * 新增页面
     */
    public function create()
    {
        $json_data = $this->IngredientType->getJsonArr();

        return view('admin.ingredienttype.create',['json_data'=>$json_data]);
    }
    /*
     * 新增入库
     */
    public function store(IngredientTypeRequest $request)
    {
        $this->IngredientType->create($request->all());
        return redirect('admin/ingredienttype');
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $data= $this->IngredientType->editViewData($id);
        $json_data = $this->IngredientType->getJsonArr($data);

        return view('admin.ingredienttype.edit',['data'=>$data,'json_data'=>$json_data]);
    }
    /*
     * 编辑入库
     */
    public function update(IngredientTypeRequest $request, $id)
    {
        $this->IngredientType->updateIngredientType($request->all(),$id);
        return redirect('admin/ingredienttype');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->IngredientType->deleteIngredientType($id);
        return redirect('admin/ingredienttype');
    }
}
