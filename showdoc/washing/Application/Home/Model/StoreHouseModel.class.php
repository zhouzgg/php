<?php

namespace Home\Model;
//use Think\Model;
/**
* 库房
*/
class StoreHouseModel{

    /**
     * 获取库房
     */
    public function getAllStoreHouse()
    {
        $customer_list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where(array("b.PROP_NAME"=>C("STOREHOUSE")))
            ->select();

        return $customer_list;
    }

    /**
     * 获取部分库房
     */
    public function getSomeStoreHouse($customer_id, $department_id)
    {
        $wData["b.PROP_NAME"] = C("STOREHOUSE");
        $wData['a.CUSTOMER_ID'] = $customer_id;
        if(!empty($department_id))
        {
            $wData['a.DEPARTMENT_ID'] = array("IN", $department_id);
        }
        $customer_list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where($wData)
            ->select();
        return $customer_list;
    }
}

