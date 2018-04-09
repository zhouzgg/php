<?php

namespace Home\Model;
//use Think\Model;
use Home\Controller\TreeCommon;

/**
* 部门
*/
class DepartmentModel{

    /**
     * 获取部门
     */
    public function getDepartment($customer_id = "")
    {
        $sWhere = array();
        if(!empty($customer_id))
        {
            $sWhere['CUSTOMER_ID'] = $customer_id;
        }
        //部门列表
        $department = M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID AS id,parent_id,DEPARTMENT_NAME AS name")->where($sWhere)->select();
        //树级
        $tree = new TreeCommon("id", "parent_id", "children");

        $tree->initTree($department);

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
            $result[$key]['spread'] = true;
        }
        return $result;
    }

    /**
     * 获取部门3
     */
    public function getDepartmentThirt($customer_id = "")
    {
        $sWhere = array();
        if(!empty($customer_id))
        {
            $sWhere['CUSTOMER_ID'] = $customer_id;
        }
        //部门列表
        $department = M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID AS id,parent_id,DEPARTMENT_NAME AS name")->where($sWhere)->select();

        foreach($department as $key=>$depart)
        {
            $department[$key]['alias'] = 1;
            $department[$key]['spread'] = true;
        }

        //树级
        $tree = new TreeCommon("id", "parent_id", "children");

        $tree->initTree($department);

        $result = $tree->getTree();

        return $result;
    }

    /**
     * 获取部门2
     */
    public function getDepartmentSecond($customer_id = "")
    {
        $sWhere = array();
        if(!empty($customer_id))
        {
            $sWhere['CUSTOMER_ID'] = $customer_id;
        }
        //部门列表
        $department = M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID AS value,parent_id,DEPARTMENT_NAME AS title")->where($sWhere)->select();

        $departmentSecond = array();
        $departmentNew = array();
        $value = 0;
//        foreach($department as $key=>$depart)
//        {
//            $departids = getChildrenIds($depart['value']);
//
//            $departArray = explode(",", substr($departids[0], 2, strlen($departids[0])));
//
//            if(!empty($departArray))
//            {
//                $departmentSecond[$value] = M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID AS value,parent_id,DEPARTMENT_NAME AS title")->where(array("DEPARTMENT_ID"=>array("IN", $departArray)))->select();
//                $value++;
//            }
//        }
//
//        foreach($departmentSecond as $depart)
//        {
//            foreach($depart as $de)
//            {
//                $departmentNew[$de['value']] = $de;
//            }
//        }

        $departmentNew = $department;
        //$departmentNew = array_unique($departmentNew);
        //树级
        $tree = new TreeCommon("value", "parent_id", "data");

        $tree->initTree($departmentNew);

        $result = $tree->getTree();

        foreach($result as $key=>$res)
        {
//            if(isset($res['children']))
//            {
//                $result[$key]['children'] = tree_list_addalias($res['children']);
//            }
//            else
//            {
//                $result[$key]['alias'] = 1;
//            }
            /*if(isset($res['data']))
            {
                $result[$key]['data'] = tree_list_addliasecond($res['data']);
            }
            else
            {
                $result[$key]['data'] = [];
            }*/
            $result[$key]['checked'] = false;
            $result[$key]['disabled'] = false;
        }
        return $result;
    }

    /**
     * 获取单条部门数据
     * @param $department_id
     */
    public function getSingleDepartment($department_id)
    {
        //单条部门数据
        return M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID,DEPARTMENT_NAME")->where(array("DEPARTMENT_ID"=>array("IN", $department_id)))->select();
    }
}

