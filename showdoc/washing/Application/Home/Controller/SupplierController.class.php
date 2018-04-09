<?php
/**
 * 供应商管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/

class SupplierController extends CommonController
{
    /*新增供应商*/
    public function add_supplier()
    {
        $this->assign("title", L("_SUPPLIER_ADD_"));
        $this->display("add_supplier");
    }

    /**
     * 添加/修改供应商信息
     */
    public function post_supplier()
    {
        $supplier_id = I("post.supplier_id");                     //供应商id
        $supplier_name = I("post.supplier_name");                 //供应商名称
        $supplier_address = I("post.supplier_address");           //供应商地址
        $supplier_person = I("post.supplier_person");             //供应商联系人
        $supplier_phone = I("post.supplier_phone");               //供应商联系方式

        //供应商名称
        if(empty($supplier_name))
        {
            backResult(0, L("_SUPPLIER_ADD_NOT_NAME_"));
        }
        //供应商地址
        if(empty($supplier_address))
        {
            backResult(0, L("_SUPPLIER_ADD_NOT_ADDRESS_"));
        }
        //供应商联系人
        if(empty($supplier_person))
        {
            backResult(0, L("_SUPPLIER_ADD_NOT_PERSON_"));
        }
        //供应商联系方式
        if(empty($supplier_phone))
        {
            backResult(0, L("_SUPPLIER_ADD_NOT_PHONE_"));
        }
        if(empty($supplier_id))
        {
            //添加
            $addData = array(
                'SUPPLIER_NAME' => $supplier_name,
                'SUPPLIER_ADDRESS' => $supplier_address,
                'SUPPLIER_CONTACT_PERSON' => $supplier_person,
                'SUPPLIER_CONTACT_PHONE' => $supplier_phone,
                'CREATE_TIME' => date("Y-m-d H:i:s")
            );
            $ret = M("SERVICE_T_SUPPLIER")->data($addData)->add();
            if($ret)
            {
                //添加供应商属性
                $pData = array(
                    'WAREHOUSE_ID' => $ret,
                    'PROP_NAME' => C("SUPPLIER"),
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
            //编辑
            $editData = array(
                'SUPPLIER_NAME' => $supplier_name,
                'SUPPLIER_ADDRESS' => $supplier_address,
                'SUPPLIER_CONTACT_PERSON' => $supplier_person,
                'SUPPLIER_CONTACT_PHONE' => $supplier_phone,
            );
            $ret = M("SERVICE_T_SUPPLIER")->where(array("SUPPLIER_ID"=>$supplier_id))->save($editData);
            if($ret)
            {
                backResult(1, "success");
            }
            else
            {
                backResult(0, L("_ADD_ERROR_OR_NOT_EDIT_"));
            }
        }
    }

    /*供应商列表*/
    public function supplier_list()
    {
        $list = M("SERVICE_T_SUPPLIER")->field("SUPPLIER_ID,SUPPLIER_NAME,SUPPLIER_ADDRESS,SUPPLIER_CONTACT_PERSON,SUPPLIER_CONTACT_PHONE,CREATE_TIME")->select();

        $this->assign("list", $list);
        $this->assign("title", L("_SUPPLIER_LIST"));
        $this->display("supplier_list");
    }

    /**
     * 导出
     */
    public function export_supplier_list()
    {
        //列表
        $list = M("SERVICE_T_SUPPLIER")->field("SUPPLIER_ID,SUPPLIER_NAME,SUPPLIER_ADDRESS,SUPPLIER_CONTACT_PERSON,SUPPLIER_CONTACT_PHONE,CREATE_TIME")->select();

        //$header = "序号,供应商名称,负责人,联系方式,地址"."\n";
        $header = L("_SUPPLIER_NO_").",".L("_SUPPLIER_NAME_").",".L("_SUPPLIER_PRINCIPAL_").",".L("_SUPPLIER_PHONE_").",".L("_SUPPLIER_ADDRESS_")."\n";

        $str = iconv('utf-8','GBK', $header);
        foreach($list as $li)
        {
            $supplier_id = $li['SUPPLIER_ID'];                                     //序号
            $supplier_name = iconv('utf-8','GBK', $li['SUPPLIER_NAME']);                               //供应商名称
            $supplier_contact_person = iconv('utf-8','GBK', $li['SUPPLIER_CONTACT_PERSON']);             //负责人
            $supplier_contact_phone = iconv('utf-8','GBK', $li['SUPPLIER_CONTACT_PHONE']);               //联系方式
            $supplier_customer_name = iconv('utf-8','GBK', $li['SUPPLIER_ADDRESS']);                   //地址

            $str .= $supplier_id . "," . $supplier_name . "," . $supplier_contact_person . "," . $supplier_contact_phone. "\t" . ","   . $supplier_customer_name . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_SUPPLIER_LIST").'-'.time().'.csv');

        explode_csv($filename, $str);
    }
}