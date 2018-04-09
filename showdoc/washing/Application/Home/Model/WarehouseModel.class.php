<?php

namespace Home\Model;
//use Think\Model;
/**
* 库房
*/
class WarehouseModel{

    /**
     * 获取全部客户
     */
    public function getHouseName($id)
    {
        if(empty($id))
        {
            return array();
        }
        return M("SERVICE_T_WAREHOUSE_ID")->field("WAREHOUSE_NAME")->where(array("WAREHOUSE_ID"=>$id))->find();
    }

    /**
     * 获取部分部门数据
     * @param $department_id
     */
    public function getSomeHouse($id)
    {
        $wData["b.PROP_NAME"] = C("STOREHOUSE");

        if(!empty($id))
        {
            $wData['a.WAREHOUSE_ID'] = array("IN", $id);
        }
        $storehouse_list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where($wData)
            ->select();

        return $storehouse_list;
    }
}

