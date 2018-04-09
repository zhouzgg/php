<?php

namespace Home\Model;
//use Think\Model;
/**
* 客户
*/
class CustomerModel extends CommonModel{

    /**
     * 获取全部客户
     */
    public function getAllCustomer()
    {
        $dWhere = array();
        //财务查出他的客户
        if(in_array(parent::getIdentity(), array("FINANCEROLE","LINENROLE")))
        {
            $customerid = M("SERVICE_T_USER_CUSTOMER")->field("CUSTOMER_ID")->where(array("USER_ID"=>session('USER_ID')))->find();
            $dWhere['a.CUSTOMER_ID'] = $customerid['CUSTOMER_ID'];
        }
        $customer_list = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID AS WAREHOUSE_ID,a.CUSTOMER_NAME AS WAREHOUSE_NAME")
            ->where($dWhere)
            ->select();
        return $customer_list;
    }

    /**
     * 获取单条客户
     * @param $customer_id
     */
    public function getSingleCustomer($customer_id)
    {
        $customer_list = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID AS WAREHOUSE_ID,a.CUSTOMER_NAME AS WAREHOUSE_NAME")
            ->where(array("CUSTOMER_ID"=>$customer_id))
            ->find();
        return $customer_list;
    }
}

