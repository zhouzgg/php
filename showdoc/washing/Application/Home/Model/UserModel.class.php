<?php

namespace Home\Model;
//use Think\Model;
use Home\Controller\TreeCommon;
/**
* 用户相关
*/
class UserModel{

    /**
     * 确认用户是否存在
     */
    public function checkUser($data)
    {
        if(empty($data))
        {
            return false;
        }
        $userInfo = M("SERVICE_T_USER")->field("USER_ID")->where($data)->find();
        if(!empty($userInfo))
        {
            return $userInfo;
        }
        else
        {
            return false;
        }
    }

    /**
     * 获取权限树形结构
     */
    public function getFunctionTree($selectData)
    {
        //功能列表
        $department = M("SERVICE_T_FUNCTION")->field("FUNCTION_ID AS id,PARENT_ID as pId,FUNCTION_NAME AS name")->select();
        //树级
        $tree = new TreeCommon("id", "pId", "children");

        $tree->initTree($department);

        $result = $tree->getTree();

        foreach($result as $key=>$res)
        {
            if(isset($res['children']))
            {
                $result[$key]['children'] = $this->childFunction($res['children'], $selectData);
                $result[$key]['open'] = true;
            }
            if(isset($selectData[$res['id']]))
            {
                $result[$key]['checked'] = true;
            }
        }
        return $result;
    }

    /**
     * 子菜单
     */
    public function childFunction($result, $selectData)
    {
        foreach($result as $key=>$res)
        {
            if(isset($res['children']))
            {
                return $this->childFunction($res['children']);
                $result[$key]['open'] = true;
            }
            if(isset($selectData[$res['id']]))
            {
                $result[$key]['checked'] = true;
            }
        }
        return $result;
    }
}

