<?php

namespace Home\Model;
//use Think\Model;
use Home\Controller\TreeCommon;

/**
 * 布草类别
 */
class ClassifyModel{

    /**
     * 获取全部类别
     */
    public function getAllClassify()
    {
        $list = M("SERVICE_T_LINEN")->field("LINEN_ID,LINEN_NAME,PARENT_ID")->select();
        return $list;
    }

    /**
     * 类别树形数据
     */
    public function getClassifyTree($classifyId)
    {

        //类别列表
        $linen = M()->table("SERVICE_T_LINEN")->field("LINEN_ID AS id,parent_id,LINEN_NAME AS name")->where(array("PARENT_ID"=>$classifyId))->select();

        foreach($linen as $key=>$li)
        {
            //有子级
            if($this->checkChildren($li['id']))
            {
                $linen[$key]['isParent'] = true;
            }
            else
            {
                $linen[$key]['isParent'] = false;
            }
        }
        return $linen;
    }

    /**
     * 检查是否有子级
     */
    public function checkChildren($classifyId)
    {
        $linen = M("SERVICE_T_LINEN")->field("LINEN_ID")->where(array("PARENT_ID"=>$classifyId))->find();
        if(empty($linen))
        {
            return false;
        } else {
            return true;
        }
    }

    /**
     * 根据客户id获取对应分类
     * @param $customer_id
     */
    public function getClassifyByCustomer($customer_id)
    {
        return M("SERVICE_T_LINEN")->field("LINEN_ID,LINEN_NAME,PARENT_ID")->where(array("CUSTOMER_ID"=>$customer_id))->select();
    }
    /**
     * 类别树形数据
     */
    /*public function getClassifyTree()
    {
        //类别列表
        $linen = M("SERVICE_T_LINEN")->field("LINEN_ID AS id,parent_id,LINEN_NAME AS name")->select();
        //树级
        $tree = new TreeCommon("id", "parent_id", "children");

        $tree->initTree($linen);

        $result = $tree->getTree();

        foreach($result as $key=>$res)
        {
            if(isset($res['children']))
            {
                $result[$key]['children'] = tree_list_addalias($res['children']);
            }
            else
            {
                $result[$key]['alias'] = 1;
            }
        }
        return $result;
    }*/

}

