<?php

namespace Qla\Crud\app\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class BaseClassifiedModel extends Model
{
    protected static function boot()
    {
        parent::boot();

        self::saved(function ($model) {
            $model->calClassList($model->id);
        });
    }


    /**
     * 根据id得到该父id的所有子项
     *
     * @param int $id
     * @param string $with
     * @param bool $show_root
     * @return array|bool
     */
    public function getAllByParentId($id = 0, $with = '', $show_root = false)
    {
        $data = $with == '' ? $this : $this->with($with);
        if ($id == 0) {
            $data = $data->orderByRaw('parent_id,sort_id')->get()->toArray();
        } else {
            $data = $data->where('class_list', 'like', '%,'.$id.',%')->orderByRaw('parent_id,sort_id')->get()->toArray();
        }

        $arr = $this->getAllByParentIdRecursion($id, $data);

        if ($show_root == true && $id == 0) {
            $root = [];
            $root[] = [
                'id' => 0,
                'title' => '根节点',
                'parent_id' => -1,
                'class_layer' => 0,
                'pinyin' => 'gjd',
            ];
            if (count($arr) == 0) {
                return $root;
            }
            $arr = array_merge($root, array_values($arr));
        }

        if ($show_root == true && $id != 0) {
            $root = $data->where('id', $id)->get();
            $arr = array_merge($root, array_values($arr));
        }

        return $arr;
    }

    /**
     * @param $pid
     * @param array $data
     * @return array|bool
     */
    public function getAllByParentIdRecursion($pid, $data = [])
    {
        static $arrTree = [];
        if (empty($data)) {
            return [];
        }

        foreach ($data as $key => $value) {
            if ($value['parent_id'] == $pid) {
                $arrTree[] = $value;
                unset($data[$key]); //注销当前节点数据，减少已无用的遍历
                $this->getAllByParentIdRecursion($value['id'], $data);
            }
        }

        return $arrTree;
    }

    /**
     * @param int $pid
     * @param bool $show_root
     * @return array
     * @internal param string $with
     * @internal param array $arr
     */
    public function getSelectArrayByParentId($pid = 0, $show_root = false)
    {
        $arr = $this->getAllByParentId($pid, '', $show_root);
        if ($pid == 0 && $show_root) {
            $pid = -1;
        }
        $tree = $this->buildTree($arr, $pid);

        return $tree;
    }

    private function buildTree(array $elements, $parentId = 0)
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($elements, $element['id']);
                $element['children'] = $children;
                $branch[] = $element;
            }
        }

        return $branch;
    }

    /**
     * 从缓存中取得数据
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getCache()
    {
        $mg = Cache::get($this->table);
        if (isset($mg)) {

            $mg = $this->all();
            Cache::add($this->table, $mg, 30);
        }

        return $mg;
    }

    /**
     * 是否是父节点
     *
     * @return bool
     */
    public function hasChild()
    {
        $class = $this->getAttribute('class_list');
        $c = $this->where('class_list', 'like', $class.'%')->count();
        if ($c > 1) {
            return true;
        } else {
            return false;
        }
    }

    /** 根据 ",1,2,3," 格式的文本中的id得到记录
     *
     * @param $str
     * @return Collection
     */
    public function getByIds($str)
    {
        $arrGroups = dcstr2arr($str);
        $r = $this->whereIn('id', $arrGroups)->get();

        return $r;
    }

    public function calClassList($id, $init = 0)
    {
        static $classList = '';
        if ($init == 1) {
            $classList = '';
        }
        $m = $this->find($id);
        $pid = $m->parent_id;
        $classList = ','.$id.','.$classList;
        if ($pid == 0) {
            $classList = str_replace(',,', ',', $classList);
            //$this->class_list = $classList;
            //$this->class_layer = mb_substr_count($classList, ',') - 1;
            DB::table($this->table)->where('id', $this->id)->update([
                        'class_list' => $classList,
                        'class_layer' => mb_substr_count($classList, ',') - 1
                    ]);

            //$this->update(['class_list'=>$classList]);
            return $classList;
        }
        $classList = $this->calClassList($pid);

        return $classList;
    }

    public static function  setAllClassList()
    {
        foreach (self::all() as $k => $v) {
            $v->calClassList($v['id'], 1);
        }
    }

    /**
     * Get the connection of the entity.
     *
     * @return string|null
     */
    public function getQueueableConnection()
    {
        // TODO: Implement getQueueableConnection() method.
    }
}
