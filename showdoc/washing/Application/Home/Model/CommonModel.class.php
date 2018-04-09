<?php

namespace Home\Model;
//use Think\Model;
/**
* 公共
*/
class CommonModel{

    /**
     * 角色身份确认
     */
    public function getIdentity()
    {
        $role = M("SERVICE_T_USER_ROLE")->alias("A")
            ->field("B.ROLE_MARK")
            ->join("LEFT JOIN SERVICE_T_ROLE AS B ON A.ROLE_ID=B.ROLE_ID")
            ->where(array("A.USER_ID"=>session('USER_ID')))
            ->find();
        return $role['ROLE_MARK'];
    }
}

