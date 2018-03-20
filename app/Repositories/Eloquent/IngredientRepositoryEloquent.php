<?php

namespace App\Repositories\Eloquent;

use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Ingredient;
use Hash;

/**
 * Class MenuRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class IngredientRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Ingredient::class;
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
        $order['name'] = $request->input('columns.' . $request->input('order.0.column') . '.name');
        $order['dir'] = $request->input('order.0.dir', 'asc');
        $search['value'] = $request->input('search.value', '');
        $search['regex'] = $request->input('search.regex', false);
        if ($search['value']) {
            if ($search['regex'] == 'true') {//传过来的是字符串不能用bool值比较
                $this->model = $this->model->where('name', 'like', "%{$search['value']}%");
            } else {
                $this->model = $this->model->where('name', $search['value']);
            }
        }
        $count = $this->model->count();
        $this->model = $this->model->orderBy($order['name'], $order['dir']);
        $this->model = $this->model->offset($start)->limit($length)->get();

        if ($this->model) {
            foreach ($this->model as $item) {
                $item->button = $item->getActionButtons('ingredient');
            }
        }
        return [
            'draw' => $draw,
            'recordsTotal' => $count,
            'recordsFiltered' => $count,
            'data' => $this->model
        ];
    }

    public function editViewData($id)
    {
        $data = $this->find($id, ['id', 'name', 'sort'])->toArray();
        if ($data) {
            return $data;
        }
        abort(404);
    }
    /*
     * 新增
     */
    public function create(array $attr)
    {
        $data = DB::table('ingredient')->where('name', $attr['name'])->first();
        if (!empty($data)) {
            abort(500, '食材已存在！');
        }
        $cooking_ways = new Ingredient();
        $cooking_ways->name = $attr['name'];
        $cooking_ways->sort = $attr['sort'];
        $cooking_ways->cTime = date("Y-m-d H:i:s");
        $cooking_ways->save();
        flash('食材新增成功', 'success');
    }
    /*
     * 修改
     */
    public function updateIngredient(array $attr, $id)
    {
        $data = DB::table('ingredient')->where('name', $attr['name'])->first();

        if (!empty($data) && $data->id!=$id) {
            abort(500, '食材已存在！');
        }
        $res = $this->update($attr, $id);

        if ($res) {
            flash('修改成功!', 'success');
        } else {
            flash('修改失败!', 'error');
        }
        return $res;
    }

    public function deleteIngredient($id){
        $res = $this->delete($id);
        if ($res) {
            flash('操作成功!', 'success');
        } else {
            flash('操作成功!', 'error');
        }
    }

}
