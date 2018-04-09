<?php

namespace Home\Model;
//use Think\Model;
/**
* 洗涤厂
*/
class WashingplantsModel{

    /**
     * 获取洗涤厂
     */
    public function getAllWashing()
    {
        $customer_list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where(array("b.PROP_NAME"=>C("WASHINGPLANTS")))
            ->select();

        return $customer_list;
    }

    /**
     * 获取部分洗涤厂
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

    /**
     * 获取单条洗涤厂
     * @param $washing_id
     */
    public function getSingleWashing($washing_id)
    {
        $wData["b.PROP_NAME"] = C("WASHINGPLANTS");
        $wData['a.WAREHOUSE_ID'] = $washing_id;
        if(!empty($department_id))
        {
            $wData['a.DEPARTMENT_ID'] = array("IN", $department_id);
        }
        return M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where($wData)
            ->find();
    }
}

