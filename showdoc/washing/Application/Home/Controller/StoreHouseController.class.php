<?php

/**
 * 仓库管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class StoreHouseController extends CommonController {

    //private $PROPERTY = "STOREHOUSE";

    /*新增仓库*/
    public function add_storehouse()
    {
        $this->assign("title", L("_ADD_STOREHOUSE_"));
        $this->assign("active","layui-nav-itemed");
        $this->assign("active2","1");
        $this->display("add_storehouse");
    }

    /**
     * 提交新增仓库
     */
    public function post_storehouse()
    {
        $warehouse_id = I("post.warehouse_id");
        $customer_id = I("post.customer_id");                                            //客户id
        $department_id = I("post.department_id");                                        //部门id
        $department_name = I("post.department_name");                                   //仓库名称
        $department_address = I("post.department_address");                             //仓库地址
        $department_contact_person = I("post.department_contact_person");              //联系人
        $department_contact_phone = I("post.department_contact_phone");                //联系方式

        if(empty($customer_id) && empty($warehouse_id))
        {
            backResult(0, L("_NOT_SELECT_STOREHOUSE_CUSTOMER_"));
        }
        if(empty($department_id))
        {
            backResult(0, L("_NOT_SELECT_STOREHOUSE_DEPARTMENT_"));
        }
        if(empty($department_name))
        {
            backResult(0, L("_NOT_STOREHOUSE_NAME_"));
        }
        if(empty($department_address))
        {
            backResult(0, L("_NOT_STOREHOUSE_ADDRESS_"));
        }
        if(empty($department_contact_person))
        {
            backResult(0, L("_NOT_STOREHOUSE_CONTACT_PERSON_"));
        }
        if(empty($department_contact_phone))
        {
            backResult(0, L("_NOT_STOREHOUSE_CONTACT_PHONE_"));
        }
        if(empty($warehouse_id))
        {
            //新增
            $wData = array(
                'WAREHOUSE_NAME' => $department_name,
                'CUSTOMER_ID' => $customer_id,
                'DEPARTMENT_ID' => $department_id,
                'WAREHOUSE_ADDRESS' => $department_address,
                'WAREHOUSE_CONTACT_PERSON' => $department_contact_person,
                'WAREHOUSE_CONTACT_PHONE' => $department_contact_phone,
                'REG_TIME' => date("Y-m-d H:i:s")
            );
            //添加（ps:这里的表名必须是小写）
            $ret = M("SERVICE_T_WAREHOUSE_ID")->data($wData)->add();
            if($ret)
            {
                //添加属性
                $pData = array(
                    'WAREHOUSE_ID' => $ret,
                    'PROP_NAME' => C("STOREHOUSE"),
                    'PROP_VALUE' => 1
                );
                M("SERVICE_T_WAREHOUSE_PROPERTY")->data($pData)->add();

                backResult(1, L("_ADD_SUCCESS_"));
            }
            else
            {
                backResult(0, L("_ADD_ERROR_"));
            }
        }
        else
        {
            $wData = array(
                'WAREHOUSE_NAME' => $department_name,
                'WAREHOUSE_ADDRESS' => $department_address,
                'WAREHOUSE_CONTACT_PERSON' => $department_contact_person,
                'WAREHOUSE_CONTACT_PHONE' => $department_contact_phone
            );
            if(!empty($department_id))
            {
                $wData['DEPARTMENT_ID'] = $department_id;
            }
            //编辑
            M("SERVICE_T_WAREHOUSE_ID")->where(array("WAREHOUSE_ID"=>$warehouse_id))->save($wData);

            backResult(1, L("_EDIT_SUCCESS_"));
        }
    }

    /**
     * 客户名称列表
     */
    public function customer_name_list()
    {
        $customer_name = I("post.customer_name");                   //客户名称
        $cWhere = array();
        if(!empty($customer_name))
        {
            $cWhere['a.CUSTOMER_NAME'] = array("LIKE", "%".$customer_name."%");
        }
        //$cWhere['b.PROP_NAME'] = C("CUSTOMER");

        $list = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID AS WAREHOUSE_ID,a.CUSTOMER_NAME AS WAREHOUSE_NAME")
            ->where($cWhere)
            ->order("a.CUSTOMER_ID DESC")
            ->select();

        backResult(1, "success", $list);
    }

    /**
     * 管理部门
     */
    public function department_name_list()
    {
        $department_name = I("post.department_name");                   //部门名称
        $customer_id = I("param.customer_id");                          //客户名称
        $dWhere = array();
        if(!empty($department_name))
        {
            $dWhere['DEPARTMENT_NAME'] = array("LIKE", "%".$department_name."%");
        }
        $dWhere['CUSTOMER_ID'] = $customer_id;
        $list = M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID,DEPARTMENT_NAME")->where($dWhere)->select();

        backResult(1, "success", $list);
    }

    /*仓库列表*/
    public function storehouse_list(){
        //列表
        $list = $this->get_storehouse_data();
        //部门
        $departmentList = M("SERVICE_T_DEPARTMENT")->field("DEPARTMENT_ID,DEPARTMENT_NAME")->select();

        $this->assign("list", $list);
        $this->assign("departmentList", $departmentList);
        $this->assign("title", L("_STOREHOUSE_LIST_"));
        $this->assign("active","layui-nav-itemed");
        $this->assign("active2","2");
        $this->display("storehouse_list");
    }

    /**
     * 导出
     */
    public function export_storehouse_list()
    {
        //列表
        $list = $this->get_storehouse_data();

        //$header = "客户,部门,仓库编号,名称,地址,联系人,联系方式"."\n";
        $header = L("_STOREHOUSE_CUSTOMER_").",".L("_STOREHOUSE_DEPARTMENT_").",".L("_STOREHOUSE_NO_").",".L("_STOREHOUSE_NAME_").",".L("_STOREHOUSE_ADDRESS_").",".L("_STOREHOUSE_PERSON_").",".L("_STOREHOUSE_PHONE_")."\n";

        $str = iconv('utf-8','GBK', $header);
        foreach($list as $li)
        {
            $customer_name = iconv('utf-8','GBK', $li['CUSTOMER_NAME']);                                     //客户
            $department_name = iconv('utf-8','GBK', $li['DEPARTMENT_NAME']);                                 //部门名称
            $warehouse_id = $li['WAREHOUSE_ID'];                                                                 //仓库编号
            $warehouse_name = iconv('utf-8','GBK', $li['WAREHOUSE_NAME']);                                   //仓库名称
            $warehouse_address = iconv('utf-8','GBK', $li['WAREHOUSE_ADDRESS']);
            $warehouse_contact_person = iconv('utf-8','GBK', $li['WAREHOUSE_CONTACT_PERSON']);             //联系人
            $warehouse_contact_phone = $li['WAREHOUSE_CONTACT_PHONE'];                                     //联系方式

            $str .= $customer_name . "," . $department_name . "," . $warehouse_id . "," . $warehouse_name . ","  . $warehouse_address . "," . $warehouse_contact_person . "," . $warehouse_contact_phone . "\t" . ","  . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_STOREHOUSE_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }

    /**
     * 仓库数据
     */
    public function get_storehouse_data()
    {
        $list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME,a.WAREHOUSE_ADDRESS,a.WAREHOUSE_CONTACT_PERSON,a.WAREHOUSE_CONTACT_PHONE,a.DEPARTMENT_ID,d.CUSTOMER_NAME,c.DEPARTMENT_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON b.WAREHOUSE_ID=a.WAREHOUSE_ID")
            ->join("LEFT JOIN SERVICE_T_DEPARTMENT AS c ON c.DEPARTMENT_ID=a.DEPARTMENT_ID")
            ->join("LEFT JOIN SERVICE_T_CUSTOMER AS d ON d.CUSTOMER_ID=a.CUSTOMER_ID")
            ->where(array("b.PROP_NAME"=>C("STOREHOUSE")))
            ->group("a.WAREHOUSE_ID,a.WAREHOUSE_NAME,a.WAREHOUSE_ADDRESS,a.WAREHOUSE_CONTACT_PERSON,a.WAREHOUSE_CONTACT_PHONE,a.DEPARTMENT_ID,d.CUSTOMER_NAME,c.DEPARTMENT_NAME")
            ->select();

        return $list;
    }

    /*详情*/
    public function storehouse_list_details()
    {
        $lid = I("get.lid");                                            //仓库id
        $order_no = I("get.no");                                        //订单编号
        $start_time = I("get.start");                                   //开始时间
        $end_time = I("get.end");                                       //结束时间

        //验证
        if(!empty($lid) && is_numeric($lid))
        {
            //客户信息
            $storehouseDetail = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
                ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME,a.WAREHOUSE_ADDRESS,a.WAREHOUSE_CONTACT_PERSON,a.WAREHOUSE_CONTACT_PHONE,a.WAREHOUSE_LOGO,a.REG_TIME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
                ->where(array("b.PROP_NAME"=>C("STOREHOUSE"), "a.WAREHOUSE_ID"=>$lid))
                ->find();

            if(!empty($storehouseDetail) && !empty($order_no) && !empty($start_time) && !empty($end_time))
            {
                $detailList = M("SERVICE_T_RECEIPT_MAIN")
                    ->field("RECEIPT_MAIN_ID,LINEN_COUNT,CREATE_TIME")
                    ->where(array("RECEIPT_MAIN_ID"=>$order_no, "CREATE_TIME"=>array(array("GT", $start_time),array("LT", $end_time))))
                    ->select();
            }
        }
        $this->assign("storehouseDetail", $storehouseDetail);
        $this->assign("detailList", $detailList);
        $this->assign("title", L("_STOREHOUSE_LIST_DETAIL_"));
        $this->display("storehouse_list_details");
    }
}