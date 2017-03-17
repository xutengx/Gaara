<?php

namespace App\admin\Model;

class categoryModel extends \Main\Core\Model {

    // 获取 pid 的所有分类类
    public function get_category($pid) {
        $arr = $this->get_children_by_pid($pid);
        foreach ($arr as $v) {
            if ($re = $this->get_children_by_pid($v['id'])) {
                $arr['children'] = $re;
            }
        }
    }

    // 获取 pid 的子类
    private function get_children_by_pid($pid) {
        return $this->where([
                    'pid' => ':pid'
                ])->getAll([
                    ':pid' => $pid
        ]);
    }

    // 查询所有分类, 以key链接父子节点
    public function get_all_category($key) {
        $arr = [];
        $re = $this->where([
                    'pid' => '0'
                ])->getAll();
        foreach ($re as $v) {
            
        }
    }

    function find_tree_child($a, $id, $p = "pid", $e = array()) {
        $children = array();
        foreach ($a as $k => $v) {
            if (isset($e[$v["id"]]))
                $v["checked"] = "true";
            if ($v[$p] == $id) {
                $children[] = $v;
            }
        }
        return $children;
    }

    function make_ultree($data, $root, $i = "id", $p = "pid", $n = "children", $e = array()) {
        $children = find_tree_child($data, $root, $p, $e);
        if (empty($children)) {
            return false;
        }
        foreach ($children as $k => $c) {
            $rtree = make_ultree($data, $c[$i], $i, $p, $n, $e);
            if ($rtree) {
                $children[$k][$n] = $rtree;
            } else {
                $children[$k][$n] = "";
            }
        }
        return $children;
    }
}
