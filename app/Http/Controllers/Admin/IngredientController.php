<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\IngredientRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\IngredientRepositoryEloquent  as IngredientRepository;

class IngredientController extends Controller
{
    public $Ingredient;
    public $role;
    public function __construct(IngredientRepository $IngredientRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:ingredient');
        $this->Ingredient = $IngredientRepository;
        $this->role = $roleRepository;
    }

    /**
     * 列表页
     */
    public function index()
    {
        $role = auth('admin')->user()->roles->toArray()[0]['display_name'];
        return view('admin.ingredient.index');
    }
    /*
     * 新增页面
     */
    public function create()
    {
        return view('admin.ingredient.create');
    }
    /*
     * 新增入库
     */
    public function store(IngredientRequest $request)
    {
        $this->Ingredient->create($request->all());
        return redirect('admin/ingredient');
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $data= $this->Ingredient->editViewData($id);

        return view('admin.ingredient.edit',compact('data'));
    }
    /*
     * 编辑入库
     */
    public function update(IngredientRequest $request, $id)
    {
        $this->Ingredient->updateIngredient($request->all(),$id);
        return redirect('admin/ingredient');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->Ingredient->deleteIngredient($id);
        return redirect('admin/ingredient');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->Ingredient->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
