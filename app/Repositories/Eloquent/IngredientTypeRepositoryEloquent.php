<?php

namespace App\Repositories\Eloquent;

use DB;
use Prettus\Repository\Eloquent\BaseRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use App\Models\IngredientType;
use Hash;

/**
 * Class MenuRepositoryEloquent
 * @package namespace App\Repositories\Eloquent;
 */
class IngredientTypeRepositoryEloquent extends BaseRepository
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return IngredientType::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function getAll($columns = ['*'])
    {
        $res = $this->orderBy('sort','ASC')->all($columns)->toArray();
        $list = $this->sortList($res);
        foreach ($list as $key => $value) {
            $list[$key]['button'] = $this->model->getActionButtons('ingredienttype',$value['id']);
        }
        return $list;

    }
    //获取前端需要的JSON数组
    public function getJsonArr($data =''){
        $field = ['id','name','pid','cTime'];
        $ingredienttype = $this->getAll($field);
        $json_data =[];
        foreach($ingredienttype as $value){
            //拼接jstree需要的数组数据
            $arr = ['id'=>$value['id'],'text'=>$value['name']];
            if($value['pid']==0){
                $arr['parent'] = '#';
            }else{
                $arr['parent'] = $value['pid'];
            }

            //如果循环的父级ID等于编辑数据的ID 或循环的ID等于编辑数据的ID 就设置禁止选择
            if(!empty($data)){
                $childrens = $this->getChildren($data['id']);
                if(in_array($value['id'],$childrens)){
                    $arr['state']['disabled'] = 'true';
                }
            }

            //如果编辑数据的父ID等于循环的ID就设置选中
            if(!empty($data) && $value['id']==$data['pid']){
                $arr['state']['opened'] = 'true';
                $arr['state']['selected'] = 'true';
            }
            $json_data[] =$arr;
        }
        return $json_data;
    }

    /* 无限级分类树 */
    public function tree($data,$pid=0,$level=1)
    {
        static $treeArr = array();
        foreach ($data as $v)
        {
            if ($v['pid'] == $pid)
            {
                $v['level'] = $level;
                $treeArr[] = $v;
                $this->tree($data, $v['id'], $level + 1);
            }
        }
        return $treeArr;
    }

    /* 传递一个id 找出下面所有子分类包括自身 */
    public function getChildren($id)
    {
        $info = $this->getAll();
        $list = $this->tree($info,$id);
        $res = array();
        foreach($list as $v)
        {
            $res[] = $v['id'];
        }
        $res[] = $id;
        return $res;
    }
    /**
     * 排序
     * @param array     $data   需要循环的数组
     * @param int       $id     获取id为$id下的子分类，0为所有分类
     * @param array     $arr    将获取到的数据暂时存储的数组中，方便数据返回
     * @return array            二维数组
     */
    protected function sortList(array $data, $id = 0, &$arr = [])
    {
        foreach ($data as $v) {
            if ($id == $v['pid']) {
                $arr[] = $v;
                $this->sortList($data, $v['id'], $arr);
            }
        }
        return $arr;
    }
    /*
     * 获取单条数据
     */
    public function editViewData($id)
    {
        $data = $this->find($id, ['id','pid', 'name', 'sort'])->toArray();
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
        $data = DB::table('ingredient_type')->where('name', $attr['name'])->first();
        if (!empty($data)) {
            abort(500, '食材分类分类已存在！');
        }
        $cooking_ways = new IngredientType();
        $cooking_ways->name = $attr['name'];
        $cooking_ways->sort = $attr['sort'];
        $cooking_ways->pid = $attr['pid'];
        $cooking_ways->cTime = date("Y-m-d H:i:s");
        $cooking_ways->save();

        flash('食材分类新增成功', 'success');
    }
    /*
     * 修改
     */
    public function updateIngredientType(array $attr, $id)
    {
        $data = DB::table('ingredient_type')->where(['name'=>$attr['name']])->first();

        if (!empty($data) && $data->id!=$id) {
            abort(500, '食材分类已存在！');
        }
        $res = $this->update($attr, $id);

        if ($res) {
            flash('修改成功!', 'success');
        } else {
            flash('修改失败!', 'error');
        }
        return $res;
    }

    public function deleteIngredientType($id){
        $data = DB::table('ingredient')->where('ingredient_type', $id)->first();
        if(!empty($data)){
            abort(500, '该分类和食材有关联关系，不允许删除！');
        }
        $res = $this->delete($id);
        if ($res) {
            flash('操作成功!', 'success');
        } else {
            flash('删除失败!', 'error');
        }
    }

    public function getMenuComposerData()
    {
        return $this->sortTreeList($this->sortList($this->getAll(['id','name','pid'])));
    }
    /**
     * 树形排序
     * @param array $data   需要排序的分类数据
     * @return array        多维数组
     */
    public function sortTreeList($data = [])
    {
        $tree = array();
        $tmpMap = array();
        foreach ($data as $k => $v) {
            $tmpMap[$v['id']] = $v;
            $tmpMap[$v['id']]['text'] = $v['name'];
            $tmpMap[$v['id']]['parent'] = $v['pid'];
        }

        foreach ($data as $value) {
            if (isset($tmpMap[$value['pid']])) {
                $tmpMap[$value['pid']]['children'][] = &$tmpMap[$value['id']];
            } else {
                $tree[] = &$tmpMap[$value['id']];
            }
        }
        unset($tmpMap);
        return $tree;
    }
}
