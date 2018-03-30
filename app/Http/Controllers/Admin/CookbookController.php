<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use App\Repositories\Eloquent\CookbookRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\CookbookRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\CookbookRepositoryEloquent  as CookbookRepository;

class CookbookController extends Controller
{
    public $Cookbook;
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
    public function __construct(CookbookRepository $Cookbook,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:cookbook');
        $this->Cookbook = $Cookbook;
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
        return view('admin.cookbook.create',['nutritive_type'=>$this->nutritive_type,'food_type'=>$this->food_type]);
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
        $data= $this->Cookbook->editViewData($id);
        $nutritive_lists= $this->Cookbook->getNutritiveByType($data['nutritive_type']); //获取选择类型下的所有营养价值
        $rel_original= $this->Cookbook->getNutritiveRelById($data['id']);  //获取关联的营养价值信息
        $rel_lists = [];
        foreach($rel_original as $v){
            $rel_lists[] = $v->nutritive_id;
        }
        return view('admin.cookbook.edit',['nutritive_type'=>$this->nutritive_type,'data'=>$data,'nutritive_lists'=>$nutritive_lists,'rel_lists'=>$rel_lists]);
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
}
