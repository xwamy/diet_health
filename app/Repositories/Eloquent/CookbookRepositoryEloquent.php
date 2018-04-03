<?php

namespace App\Repositories\Eloquent;

use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\Cookbook;
use Hash;

/**
 * Class MenuRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class CookbookRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Cookbook::class;
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
        $data = $this->find($id, ['*'])->toArray();
        if ($data) {
            return $data;
        }
        abort(404);
    }
    //根据ID获取营养价值
    public function getNutritiveById($Id)
    {
        $data = DB::table('nutritive')->where('id', $Id)->get();
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
        $data = DB::table('cookbook')->where('name', $attr['name'])->first();
        if (!empty($data)) {
            abort(500, '食谱已存在！');
        }
        $cookbook = new Cookbook();
        $cookbook->name = $attr['name'];
        $cookbook->sort = $attr['sort'];
        $cookbook->description = $attr['description'];
        $cookbook->thumb = $attr['thumb'];
        $cookbook->timer = $attr['timer'];
        $cookbook->people_num = $attr['people_num'];
        $cookbook->cooking_way_id = $attr['cooking_way_id'];
        $cookbook->taste_id = $attr['taste_id'];
        $cookbook->nutritive_id = $attr['nutritive_id'];
        $cookbook->difficulty = $attr['difficulty'];
        $cookbook->practice = $attr['practice'];
        $cookbook->skill = $attr['skill'];
        $cookbook->food_type = $attr['food_type'];
        $cookbook->publisher = '0';
        $cookbook->cTime = date("Y-m-d H:i:s");
        $cookbook->uTime = date("Y-m-d H:i:s");
        $cookbook->save();

        //新增食谱与食材的关系
        foreach($attr['ingredients'] as $v){
            $ingredients_cookbook_rel[] =
            [
                'cookbook_id'=>$cookbook->id,
                'ingredients_id'=>$v['ingredients_id'],
                'main'=>$v['main'],
            ];
        }

        DB::table('ingredients_cookbook_rel')->insert($ingredients_cookbook_rel);

        flash('食谱新增成功', 'success');
    }
    /*
     * 修改
     */
    public function updateCookbook(array $attr, $id)
    {
        $data = DB::table('cookbook')->where('name', $attr['name'])->first();

        if (!empty($data) && $data->id!=$id) {
            abort(500, '食谱已存在！');
        }
        $res = $this->update($attr, $id);

        if ($res) {
            $rel_datas = DB::table('ingredients_cookbook_rel')->where('cookbook_id', $id)->get();

            foreach($attr['ingredients'] as $v){
                $rel_data = DB::table('ingredients_cookbook_rel')->where(['ingredients_id'=>$v['ingredients_id'],'cookbook_id'=>$id,'main'=>$v['main']])->first();
                if(!empty($rel_data)){
                    // 循环删除库中的匹配到的数据
                    foreach($rel_datas as $k=>$c){
                        if($c->id == $rel_data->id){
                            unset($rel_datas[$k]);
                        }
                    }
                    continue;
                }
                //库里不存在就添加
                $ingredients_nutritive_rel[] = ['ingredients_id'=>$v['ingredients_id'],'cookbook_id'=>$id,'main'=>$v['main']];
            }
            if(!empty($ingredients_nutritive_rel)){
                DB::table('ingredients_cookbook_rel')->insert($ingredients_nutritive_rel);
            }
            //循环删除库中多余的数据
            foreach($rel_datas as $v){
                DB::table('ingredients_cookbook_rel')->where('id',$v->id)->delete();
            }

            flash('修改成功!', 'success');
        } else {
            flash('修改失败!', 'error');
        }
        return $res;
    }

    public function deleteCookbook($id){
        $data = DB::table('ingredients_cookbook_rel')->where('cookbook_id', $id)->first();
        if(!empty($data)){
            abort(500, '该食谱与食材有关联关系，不允许删除！');
        }
        $res = $this->delete($id);
        if ($res) {
            flash('操作成功!', 'success');
        } else {
            flash('删除失败!', 'error');
        }
    }

}
