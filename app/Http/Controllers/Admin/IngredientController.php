<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use App\Repositories\Eloquent\NutritiveRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\IngredientRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\IngredientRepositoryEloquent  as IngredientRepository;

class IngredientController extends Controller
{
    public $Ingredient;
    public $role;
    public $nutritive_type = [
        '1'=>['id'=>'1','display_name'=>'内补'],
        '2'=>['id'=>'2','display_name'=>'外补'],
        '3'=>['id'=>'3','display_name'=>'维生素'],
        '4'=>['id'=>'4','display_name'=>'微量元素'],
    ];
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
        return view('admin.ingredient.create',['nutritive_type'=>$this->nutritive_type]);
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
        $nutritive_lists= $this->Ingredient->getNutritiveByType($data['nutritive_type']); //获取选择类型下的所有营养价值
        $rel_original= $this->Ingredient->getNutritiveRelById($data['id']);  //获取关联的营养价值信息
        $rel_lists = [];
        foreach($rel_original as $v){
            $rel_lists[] = $v->nutritive_id;
        }
        return view('admin.ingredient.edit',['nutritive_type'=>$this->nutritive_type,'data'=>$data,'nutritive_lists'=>$nutritive_lists,'rel_lists'=>$rel_lists]);
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
