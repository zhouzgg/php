<?php
/**
 * 部门管理
 */
namespace Home\Controller;
use Home\Model\CustomerModel;
use Home\Model\DepartmentModel;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class DepartmentController extends CommonController {

    /*新增部门*/
    public function add_dt()
    {
        $customerModel = new CustomerModel();
        //客户名称列表
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customerList", $customer_list);
        $this->assign("title", L("_ADD_DEPARTMENT_"));
        $this->display("add_dt");
    }

    /**
     * 部门
     */
    public function get_department_list()
    {
        $customer_id = I("get.customer_id");                   //客户id
        //调用部门数据
        $departMentModel = new DepartmentModel();
        $result = $departMentModel->getDepartmentThirt($customer_id);
        //无上级部门
        $notResult = array(array("id" => 0,"parent_id" => 0,"name" => "无上级部门","alias" => 1,"spread"=>true));
        if(!empty($result))
        {
            $result = array_merge($notResult, $result);
            backResult(1, "success", $result);
        }
        else
        {
            backResult(1, "success", $notResult);
        }
    }

    /**
     * 部门2
     */
    public function getdepartmentlist()
    {
        $customer_id = I("get.customer_id");                   //客户id
        //调用部门数据
        $departMentModel = new DepartmentModel();
        $result = $departMentModel->getDepartmentSecond($customer_id);

        if(!empty($result))
        {
            //无上级部门
            $notResult = array(array("value" => 0,"parent_id" => 0,"title" => "全选","checked" => false,"disabled"=>false,"data"=>[]));
            $notResult[0]['data'] = $result;
            $resultnew = $notResult;
            //$resultnew = array_merge($notResult, $result);
        }
        else
        {
            $resultnew = array();//$notResult;
        }
        echo json_encode($resultnew, JSON_UNESCAPED_UNICODE);
        exit;
    }


    /**
     * 提交新增部门
     */
    public function post_department()
    {
        $customer_id = I("post.customer_id");                                            //客户id
        $parent_id = I("post.parent_id");                                               //上级部门id
        $department_name = I("post.department_name");                                   //部门名称
        $department_contact_phone = I("post.department_contact_phone");                //联系方式
        $department_contact_person = I("post.department_contact_person");              //联系人

        if(empty($customer_id))
        {
            backResult(0, L("_NOT_SELECT_CUSTOMER_"));
        }
        if(empty($parent_id))
        {
            $parent_id = "";
        }
        if(empty($department_name))
        {
            backResult(0, L("_NOT_DEPARTMENT_NAME_"));
        }
        if(empty($department_contact_person))
        {
            backResult(0, L("_NOT_DEPARTMENT_CONTACT_PERSON_"));
        }
        if(empty($department_contact_phone))
        {
            backResult(0, L("_NOT_DEPARTMENT_CONTACT_PHONE_"));
        }
        $wData['CUSTOMER_ID'] = $customer_id;
        $wData['PARENT_ID'] = $parent_id;
        $wData['DEPARTMENT_NAME'] = $department_name;
        $wData['DEPARTMENT_CONTACT_PERSON'] = $department_contact_person;
        $wData['DEPARTMENT_CONTACT_PHONE'] = $department_contact_phone;
        //添加（ps:这里的表名必须是小写）
        $ret = M("SERVICE_T_DEPARTMENT")->add($wData);
        if($ret)
        {
            backResult(1, L("_ADD_SUCCESS_"));
        }
        else
        {
            backResult(0, L("_ADD_ERROR_"));
        }
    }

    /*部门列表*/
    public function dt_list()
    {
        //列表
        $list = $this->get_department_data();

        $customerNameList = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID,a.CUSTOMER_NAME,a.CUSTOMER_ADDRESS,a.CUSTOMER_CONTACT_PERSON,a.CUSTOMER_CONTACT_PHONE,a.CUSTOMER_LOGO")
            ->select();

        $this->assign("list", $list);
        $this->assign("customer_id", I("get.customer_id"));
        $this->assign("customerNameList", $customerNameList);
        $this->assign("title", L("_DEPARTMENT_LIST_"));
        $this->display("dt_list");
    }

    /**
     * 导出
     */
    public function export_department_list()
    {
        //列表
        $list = $this->get_department_data();
        //$header = "序号,部门名称,联系人,联系方式,所属客户,所属部门"."\n";
        $header = L("_CUSTOMER_ID_").",".L("_CUSTOMER_INPUT_DEPARTMENT_").",".L("_CUSTOMER_CONTACT_PERSON_").",".L("_CUSTOMER_CONTACT_PHONE_").",".L("_CUSTOMER_SUBSIDIARY_").",".L("_CUSTOMER_DEPARTMENT_")."\n";

        $str = iconv('utf-8','gb2312', $header);
        foreach($list as $li)
        {
            $department_id = iconv('utf-8','GBK', $li['DEPARTMENT_ID']);                                     //部门id
            $department_name = iconv('utf-8','GBK', $li['DEPARTMENT_NAME']);                               //部门名称
            $department_contact_person = iconv('utf-8','GBK', $li['DEPARTMENT_CONTACT_PERSON']);             //联系人
            $department_contact_phone = iconv('utf-8','GBK', $li['DEPARTMENT_CONTACT_PHONE']);               //联系方式
            $department_customer_name = iconv('utf-8','GBK', $li['CUSTOMER_NAME']);                             //所属客户
            $department_parent_name = iconv('utf-8','GBK', $li['PARENT_NAME']);                               //所属部门

            $str .= $department_id . "," . $department_name . "," . $department_contact_person . "," . $department_contact_phone. "\t" . "," . "\t"  . $department_customer_name . "," . $department_parent_name . "," . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_DEPARTMENT_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }

    /**
     * 部门数据
     */
    public function get_department_data()
    {
        $customer_id = I("get.customer_id");                        //客户id
        $dWhere = array();
        if(!empty($customer_id) && is_numeric($customer_id))
        {
            $dWhere['a.CUSTOMER_ID'] = $customer_id;

            $list = M("SERVICE_T_DEPARTMENT")->alias("a")
                ->field("a.DEPARTMENT_ID,a.DEPARTMENT_NAME,a.DEPARTMENT_CONTACT_PERSON,a.DEPARTMENT_CONTACT_PHONE,b.CUSTOMER_NAME,c.DEPARTMENT_NAME as PARENT_NAME")
                ->join("left join SERVICE_T_CUSTOMER as b on b.CUSTOMER_ID=a.CUSTOMER_ID")
                ->join("left join SERVICE_T_DEPARTMENT as c on c.DEPARTMENT_ID=a.PARENT_ID")
                ->where($dWhere)
                ->group("DEPARTMENT_ID")
                ->select();
        }
        return $list;
    }

}