<?php

namespace App\Repositories\Eloquent;

use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Repositories\Contracts\UserRepository as UserRepositoryInterface;
use App\Models\Users;
use Hash;

/**
 * Class MenuRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class UserRepositoryEloquent extends BaseRepository implements UserRepositoryInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Users::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function ajaxIndex($request)
    {
        $draw = $request->input('draw', 1);
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $order['name'] = "users.".$request->input('columns.' . $request->input('order.0.column') . '.name');
        $order['dir'] = $request->input('order.0.dir', 'asc');
        $search['value'] = $request->input('search.value', '');
        $search['regex'] = $request->input('search.regex', false);
        if ($search['value']) {
            if ($search['regex'] == 'true') {//传过来的是字符串不能用bool值比较
                $this->model = $this->model->where('users.tel', 'like', "%{$search['value']}%")->orWhere('users.name', 'like', "%{$search['value']}%");
            } else {
                $this->model = $this->model->where('users.tel', $search['value'])->orWhere('users.name', $search['value']);
            }
        }
        $count = $this->model->count();
        $this->model = $this->model
            ->leftJoin('provinces as p', 'users.province', '=', 'p.provinceid')
            ->leftJoin('city as c', 'users.city', '=', 'c.cityid')
            ->leftJoin('areas as a', 'users.area', '=', 'a.areaid')
            ->orderBy($order['name'], $order['dir']);
        $this->model = $this->model->offset($start)->limit($length)->get();

        if ($this->model) {
            foreach ($this->model as $item) {
                $item->button = $item->getActionButtonsOnlyDel('users');
            }
        }
        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $this->model
        ];
    }

    public function deleteUser($id){
        return false;
        $res = $this->delete($id);
        if ($res) {
            flash('操作成功!', 'success');
        } else {
            flash('删除失败!', 'error');
        }
    }

}
