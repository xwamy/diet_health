<?php

namespace App\Http\Controllers\Admin;

use App\Repositories\Eloquent\RoleRepositoryEloquent;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\UserRepositoryEloquent as UserRepository;

class UsersController extends Controller
{
    public $User;
    public $role;
    public function __construct(UserRepository $UsersRepository,RoleRepositoryEloquent $roleRepository)
    {
        $this->middleware('CheckPermission:users');
        $this->User = $UsersRepository;
        $this->role = $roleRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = auth('admin')->user()->roles->toArray()[0]['display_name'];
        return view('admin.users.index');
    }

    /**
     * 删除
     */
    public function destroy($id)
    {
        $this->User->deleteUser($id);
        return redirect('admin/users');
    }

    public function ajaxIndex(Request $request)
    {
        $result = $this->User->ajaxIndex($request);
        return response()->json($result,JSON_UNESCAPED_UNICODE);
    }
}
