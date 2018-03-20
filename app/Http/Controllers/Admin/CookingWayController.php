<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\CookingWayRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\CookingWayRepositoryEloquent  as CookingWayRepository;

class CookingWayController extends Controller
{
    public $CookingWay;
    public $role;
    public function __construct(CookingWayRepository $CookingWayRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:cookingway');
        $this->CookingWay = $CookingWayRepository;
        $this->role = $roleRepository;
    }

    /**
     * 列表页
     */
    public function index()
    {
        $role = auth('admin')->user()->roles->toArray()[0]['display_name'];
        return view('admin.cookingway.index');
    }
    /*
     * 新增页面
     */
    public function create()
    {
        return view('admin.cookingway.create');
    }
    /*
     * 新增入库
     */
    public function store(CookingWayRequest $request)
    {
        $this->CookingWay->create($request->all());
        return redirect('admin/cookingway');
    }

    /*
     * 编辑页面
     */
    public function edit($id)
    {
        $cookingway = $this->CookingWay->editViewData($id);

        return view('admin.cookingway.edit',compact('cookingway'));
    }
    /*
     * 编辑入库
     */
    public function update(CookingWayRequest $request, $id)
    {
        $this->CookingWay->updateCookingway($request->all(),$id);
        return redirect('admin/cookingway');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->CookingWay->delete($id);
        return redirect('admin/cookingway');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->CookingWay->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
