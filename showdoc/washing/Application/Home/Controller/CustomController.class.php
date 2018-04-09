<?php
/**
 * 客户管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class CustomController extends CommonController {

    //private $PROPERTY = "CUSTOMER";                                 //库房表对应的属性名称

    /*新增客户*/
    public function add_custom(){
        $this->assign("title", L("_ADD_CUSTOMER_"));
        $this->display("add_custom");
    }

    /*提交新增客户*/
    public function post_customer()
    {
        $customer_name = I("post.customer_name");                                     //客户名称
        $customer_address = I("post.customer_address");                              //客户地址
        $customer_contact_person = I("post.customer_contact_person");               //联系人
        $customer_contact_phone = I("post.customer_contact_phone");                  //联系方式
        $customer_logo = I("post.customer_logo");                                     //logo地址

        if(empty($customer_name))
        {
            backResult(0, L("_NOT_customer_NAME_"));
        }
        if(empty($customer_address))
        {
            backResult(0, L("_NOT_CUSTOMER_ADDRESS_"));
        }
        if(empty($customer_contact_person))
        {
            backResult(0, L("_NOT_CUSTOMER_CONTACT_PERSON_"));
        }
        if(empty($customer_contact_phone))
        {
            backResult(0, L("_NOT_CUSTOMER_CONTACT_PHONE_"));
        }
        if(empty($customer_logo))
        {
            backResult(0, L("_NOT_CUSTOMER_LOGO_"));
        }
        $wData = array(
            'CUSTOMER_NAME' => $customer_name,
            'CUSTOMER_ADDRESS' => $customer_address,
            'CUSTOMER_CONTACT_PERSON' => $customer_contact_person,
            'CUSTOMER_CONTACT_PHONE' => $customer_contact_phone,
            'CUSTOMER_LOGO' => $customer_logo,
            'REG_TIME' => date("Y-m-d H:i:s")
        );
        //添加（ps:这里的表名必须是小写）
        $ret = M("SERVICE_T_CUSTOMER")->data($wData)->add();

        if($ret)
        {
            //添加属性
//            $pData = array(
//                'WAREHOUSE_ID' => $ret,
//                'PROP_NAME' => C("CUSTOMER"),
//                'PROP_VALUE' => 1
//            );
//            M("SERVICE_T_WAREHOUSE_PROPERTY")->data($pData)->add();

            backResult(1, L("_ADD_SUCCESS_"));
        }
        else
        {
            backResult(0, L("_ADD_ERROR_"));
        }
    }

    /*客户列表*/
    public function custom_list()
    {
        $list = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID,a.CUSTOMER_NAME,a.CUSTOMER_ADDRESS,a.CUSTOMER_CONTACT_PERSON,a.CUSTOMER_CONTACT_PHONE,a.CUSTOMER_LOGO")
            ->select();

        $this->assign("list", $list);
        $this->assign("title", L("_CUSTOMER_LIST_"));
        $this->display("custom_list");
    }

    /**
     * 导出
     */
    public function export_customer_list()
    {
        //列表
        $list = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID,a.CUSTOMER_NAME,a.CUSTOMER_ADDRESS,a.CUSTOMER_CONTACT_PERSON,a.CUSTOMER_CONTACT_PHONE,a.CUSTOMER_LOGO")
            ->select();

        //"序号,名称,地址,联系人,联系方式"."\n";
        $header = L("_CUSTOMER_NO_").','.L("_CUSTOMER_NAME_").','.L("_CUSTOMER_ADDRESS_").','.L("_CUSTOMER_PERSON_")."\t".','.L("_CUSTOMER_PHONE_").','.L("_CUSTOMER_SETTING_")."\n";

        $str = iconv('utf-8','GBK', $header);
        foreach($list as $li)
        {
            $customer_id = iconv('utf-8','GBK', $li['CUSTOMER_ID']);                                   //客户id
            $customer_name = iconv('utf-8','GBK', $li['CUSTOMER_NAME']);                               //客户名称
            $customer_customer_name = iconv('utf-8','GBK', $li['CUSTOMER_ADDRESS']);                   //客户地址
            $customer_contact_person = iconv('utf-8','GBK', $li['CUSTOMER_CONTACT_PERSON']);           //联系人
            $customer_contact_phone = iconv('utf-8','GBK', $li['CUSTOMER_CONTACT_PHONE']);            //联系方式


            $str .= $customer_id . "," . $customer_name . "," . $customer_customer_name . "," . $customer_contact_person . "," . $customer_contact_phone."\t"  . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_CUSTOMER_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }
}