<?php
/**
 * Tree 树型类(无限分类)
 *
 *   $tree= new Tree($result);
 *   $arr=$tree->leaf(0);
 *   $nav=$tree->navi(15);
 *   $men=$tree->leafid(0);
 */
namespace Home\Controller;
use Think\Controller;

class TreeCommon extends Controller {

    private $originalList;
    public $pk;//主键字段名
    public $parentKey;//上级id字段名
    public $childrenKey;//用来存储子分类的数组key名

    function __construct($pk="id",$parentKey="parent",$childrenKey="children"){
        if(!empty($pk) && !empty($parentKey) && !empty($childrenKey)){
            $this->pk=$pk;
            $this->parentKey=$parentKey;
            $this->childrenKey=$childrenKey;
        }else{
            return false;
        }
    }

    //载入初始数组
    function initTree($data){
        if(is_array($data)){
            $this->originalList = $data;
        }
    }

    /**
     * 生成嵌套格式的树形数组
     * array(..."children"=>array(..."children"=>array(...)))
     */
    function getTree($root = 0){
        if(!$this->originalList){
            return false;
        }
        $originalList = $this->originalList;
        $tree = array();//最终数组
        $refer = array();//存储主键与数组单元的引用关系
        //遍历
        foreach($originalList as $k=>$v){
            if(!isset($v[$this->pk]) || !isset($v[$this->parentKey]) || isset($v[$this->childrenKey])){
                unset($originalList[$k]);
                continue;
            }
            //为每个数组成员建立引用关系
            $refer[$v[$this->pk]] = &$originalList[$k];
        }

        //遍历2
        foreach($originalList as $k=>$v){
            //根分类直接添加引用到tree中
            if($v[$this->parentKey] == $root)
            {
                $tree[] = &$originalList[$k];
            }
            else
            {
                if(isset($refer[$v[$this->parentKey]]))
                {
                    //获取父分类的引用
                    $parent = &$refer[$v[$this->parentKey]];

                    //在父分类的children中再添加一个引用成员
                    $parent[$this->childrenKey][] = &$originalList[$k];
                }
            }
        }
        return $tree;
    }
}