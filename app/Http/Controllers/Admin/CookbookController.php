<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\CookbookRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\CookbookRepositoryEloquent  as CookbookRepository;
use App\Repositories\Eloquent\IngredientRepositoryEloquent  as IngredientRepository;
use App\Repositories\Eloquent\IngredientTypeRepositoryEloquent  as IngredientTypeRepository;

class CookbookController extends Controller
{
    public $Cookbook;
    public $Ingredient;
    public $IngredientType;
    public $role;
    public $nutritive_type = [
        '1'=>['id'=>'1','display_name'=>'内补'],
        '2'=>['id'=>'2','display_name'=>'外补'],
        '3'=>['id'=>'3','display_name'=>'维生素'],
        '4'=>['id'=>'4','display_name'=>'微量元素'],
    ];
    public $food_type = [
        '1'=>['id'=>'1','name'=>'主食'],
        '2'=>['id'=>'2','name'=>'菜肴'],
        '3'=>['id'=>'3','name'=>'汤类'],
        '4'=>['id'=>'4','name'=>'饮料'],
        '5'=>['id'=>'5','name'=>'零食'],
    ];
    public function __construct(CookbookRepository $Cookbook,IngredientTypeRepository $IngredientTypeRepository,IngredientRepository $IngredientRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:cookbook');
        $this->Cookbook = $Cookbook;
        $this->Ingredient = $IngredientRepository;
        $this->IngredientType = $IngredientTypeRepository;
        $this->role = $roleRepository;
    }

    /**
     * 列表页
     */
    public function index()
    {
        $role = auth('admin')->user()->roles->toArray()[0]['display_name'];
        return view('admin.cookbook.index');
    }
    /*
     * 新增页面
     */
    public function create()
    {
        $ingredienttype = $this->IngredientType->findWhere(['pid'=>0])->toArray();
        return view('admin.cookbook.create',['nutritive_type'=>$this->nutritive_type,'food_type'=>$this->food_type,'ingredienttype'=>$ingredienttype]);
    }
    /*
     * 新增入库
     */
    public function store(CookbookRequest $request)
    {
        $this->Cookbook->create($request->all());
        return redirect('admin/cookbook');
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $data= $this->Cookbook->editViewData($id);      //获取编辑数据
        $nutritive = $this->Cookbook->getNutritiveById($data['nutritive_id']);   //获取营养价值
        $ingredienttype = $this->IngredientType->findWhere(['pid'=>0])->toArray();  //获取食材分类
        $ingredients = $this->Cookbook->getIngredientsCookbookRel($data['id']);   //获取关联的食材
        $return = [
            'nutritive_type'=>$this->nutritive_type,
            'nutritive'=>$nutritive,
            'data'=>$data,
            'food_type'=>$this->food_type,
            'ingredienttype'=>$ingredienttype
        ];
        return view('admin.cookbook.edit',$return);
    }
    /*
     * 编辑入库
     */
    public function update(CookbookRequest $request, $id)
    {
        $this->Cookbook->updateCookbook($request->all(),$id);
        return redirect('admin/cookbook');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->Cookbook->deleteCookbook($id);
        return redirect('admin/cookbook');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->Cookbook->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
    //获取下级食材分类
    public function ajaxIngredienttype(Request $request)
    {
        $IngredientType= $this->IngredientType->findWhere(['pid'=>$request->all()['parentid']])->toArray();
        $result = [];
        //拼接JS所需要的数组
        foreach($IngredientType as $v){
            $result[] = [
                'id'=>$v['id'],
                'parentid'=>$v['pid'],
                'name'=>$v['name'],
            ];
        }
        //如果分类不存在，那么就去查询食材
        if(empty($IngredientType)){
            $ingredient = $this->Ingredient->findWhere(['ingredient_type'=>$request->all()['parentid']])->toArray();
            foreach($ingredient as $v){
                $result[] = [
                    'id'=>$v['id'],
                    'parentid'=>$v['ingredient_type'],
                    'name'=>$v['name'],
                ];
            }
        }
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
