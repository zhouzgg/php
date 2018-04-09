<?php
/**
 * 丢损管理
 */
namespace Home\Controller;
use Home\Model\CustomerModel;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class LossManageController extends CommonController {

    /*报损列表*/
    public function break_list()
    {
        $status = I("param.status");                            //审核状态
        $name = I("param.name");                                //申报人

        if(!empty($name))
        {
            //报损列表数据
            $this->break_list_data($status, $name);
        }
        $customerModel = new CustomerModel();
        //客户列表
        $customerList = $customerModel->getAllCustomer();

        $this->assign("customerList", $customerList);
        $this->assign("title", L("_LOSS_BAD_LIST_"));
        $this->display("break_list");
    }

    /*报损列表数据*/
    public function break_list_data($status, $name)
    {
        //审核状态
        if(!empty($status) && is_numeric($status))
        {
            $sWhere['a.RESULT'] = $this->getStatus($status);
        }
        //申报人
        if(!empty($name))
        {
            $sWhere['d.USER_NAME'] = $name;
        }
        $sWhere['c.PROP_NAME'] = '报损库';                            //报损库属性为“报损库”

        $list = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
            ->field("a.RECEIPT_MAIN_ID,a.LAST_AUDIT_TIME,a.RESULT,b.LINEN_COUNT,b.CREATE_TIME,d.USER_NAME,f.LINEN_NAME,g.USER_NAME AS CREATE_NAME")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
            ->join("LEFT JOIN SERVICE_T_USER AS d ON d.USER_ID=a.USER_ID")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_SUB AS e ON e.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_LINEN AS f ON f.LINEN_ID=e.LINEN_ID")
            ->join("LEFT JOIN SERVICE_T_USER AS g ON g.USER_ID=b.USER_ID")
            ->where($sWhere)
            ->group("a.RECEIPT_MAIN_ID")
            ->select();

        foreach($list as $key=>$li)
        {
            $list[$key]['STATUSNAME'] = $this->getStatusName($li['RESULT']);
        }
        $this->assign("list", $list);
    }

    /**
     * 状态
     * @param $status
     */
    public function getStatus($status)
    {
        $newStatus = -1;
        switch($status)
        {
            case 1:
                //未审核
                $newStatus = -1;
                break;
            case 2:
                //审核未通过
                $newStatus = 0;
                break;
            case 3:
                //审核通过
                $newStatus = 1;
                break;
        }
        return $newStatus;
    }

    /**
     * 状态名称
     */
    public function getStatusName($status)
    {
        switch($status)
        {
            case 0:
                //审核未通过
                $newStatus = "审核未通过";
                break;
            case 1:
                //审核通过
                $newStatus = "审核通过";
                break;
            default:
                //未审核
                $newStatus = "未审核";
                break;
        }
        return $newStatus;
    }

    /*丢失列表*/
    public function lose_list()
    {
        $status = I("param.status");                            //审核状态
        $name = I("param.name");                                //申报人

        if(!empty($name))
        {
            /*丢失列表数据*/
            $this->lose_list_data($status, $name);
        }
        $this->assign("title",L("_LOSS_LIST_"));
        $this->display("lose_list");
    }

    /*丢失列表数据*/
    public function lose_list_data($status, $name)
    {
        //审核状态
        if(!empty($status) && is_numeric($status))
        {
            $sWhere['a.RESULT'] = $this->getStatus($status);
        }
        //申报人
        if(!empty($name))
        {
            $sWhere['d.USER_NAME'] = $name;
        }
        $sWhere['c.PROP_NAME'] = '丢失库';                            //报损库属性为“报损库”

        $list = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
            ->field("a.RECEIPT_MAIN_ID,a.LAST_AUDIT_TIME,a.RESULT,b.LINEN_COUNT,b.CREATE_TIME,d.USER_NAME,f.LINEN_NAME,g.USER_NAME AS CREATE_NAME")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
            ->join("LEFT JOIN SERVICE_T_USER AS d ON d.USER_ID=a.USER_ID")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_SUB AS e ON e.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_LINEN AS f ON f.LINEN_ID=e.LINEN_ID")
            ->join("LEFT JOIN SERVICE_T_USER AS g ON g.USER_ID=b.USER_ID")
            ->where($sWhere)
            ->group("a.RECEIPT_MAIN_ID")
            ->select();

        foreach($list as $key=>$li)
        {
            $list[$key]['STATUSNAME'] = $this->getStatusName($li['RESULT']);
        }
        $this->assign("list", $list);
    }

    /*丢失单详情*/
    public function lose_list_details()
    {
        $main_id = I("param.lid");                              //主单id
        $verify = I("param.verify");                            //报损审核（通过这个参数判断是否要显示审核按钮）

        if(!empty($main_id))
        {
            $sWhere['b.RECEIPT_MAIN_ID'] = $main_id;

            $sWhere['c.PROP_NAME'] = '丢失库';                            //丢失属性为“丢失库”

            $list = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.LAST_AUDIT_TIME,a.RESULT,a.EXPLANATION,b.LINEN_COUNT,b.CREATE_TIME,d.USER_NAME,e.LINEN_COUNT AS LINEN_CHILD_COUNT,f.LINEN_NAME,g.USER_NAME AS CREATE_NAME")
                ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS d ON d.USER_ID=a.USER_ID")
                ->join("LEFT JOIN SERVICE_T_RECEIPT_SUB AS e ON e.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
                ->join("LEFT JOIN SERVICE_T_LINEN AS f ON f.LINEN_ID=e.LINEN_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS g ON g.USER_ID=b.USER_ID")
                ->where($sWhere)
                ->group("a.RECEIPT_MAIN_ID")
                ->select();

            foreach($list as $key=>$li)
            {
                $list[$key]['STATUSNAME'] = $this->getStatusName($li['RESULT']);
            }
        }
        $this->assign("main_id", $main_id);
        $this->assign("detail", $list[0]);
        $this->assign("verify", $verify);
        $this->assign("title", L("_LOSS_DETAIL_"));
        $this->display("lose_list_details");
    }

    /**
     * 报损审核（与报损列表是同个页面）
     */
    public function break_verify()
    {
        $status = I("param.status");                            //审核状态
        $name = I("param.name");                                //申报人

        if(!empty($name))
        {
            //报损列表数据
            $this->break_list_data($status, $name);
        }
        $customerModel = new CustomerModel();
        //客户列表
        $customerList = $customerModel->getAllCustomer();

        $this->assign("break_verify", "1");
        $this->assign("customerList", $customerList);
        $this->assign("title", L("_LOSS_BAD_AUDIT_"));
        $this->display("break_list");
    }

    /**
     * 丢失审核（与丢失列表是同个页面）
     */
    public function lose_verify()
    {
        $status = I("param.status");                            //审核状态
        $name = I("param.name");                                //申报人

        if(!empty($name))
        {
            /*丢失列表数据*/
            $this->lose_list_data($status, $name);
        }
        $this->assign("lose_verify", "1");
        $this->assign("title", L("_LOSS_AUDIT_"));
        $this->display("lose_list");
    }

    /*报损单详情*/
    public function break_list_details()
    {
        $main_id = I("param.lid");                              //主单id
        $verify = I("param.verify");                     //报损审核（通过这个参数判断是否要显示审核按钮）

        if(!empty($main_id))
        {
            $sWhere['b.RECEIPT_MAIN_ID'] = $main_id;

            $sWhere['c.PROP_NAME'] = '报损库';                            //报损库属性为“报损库”

            $list = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.LAST_AUDIT_TIME,a.RESULT,a.EXPLANATION,b.LINEN_COUNT,b.CREATE_TIME,d.USER_NAME,e.LINEN_COUNT AS LINEN_CHILD_COUNT,f.LINEN_NAME,g.USER_NAME AS CREATE_NAME")
                ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS d ON d.USER_ID=a.USER_ID")
                ->join("LEFT JOIN SERVICE_T_RECEIPT_SUB AS e ON e.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
                ->join("LEFT JOIN SERVICE_T_LINEN AS f ON f.LINEN_ID=e.LINEN_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS g ON g.USER_ID=b.USER_ID")
                ->where($sWhere)
                ->group("a.RECEIPT_MAIN_ID")
                ->select();

            foreach($list as $key=>$li)
            {
                $list[$key]['STATUSNAME'] = $this->getStatusName($li['RESULT']);
            }
        }
        $this->assign("main_id", $main_id);
        $this->assign("detail", $list[0]);
        $this->assign("verify", $verify);
        $this->assign("title", L("_LOSS_BAD_DETAIL_"));
        $this->display("break_list_details");
    }

    /**
     * 提交审核
     */
    public function submit_verify()
    {
        $status = I("post.status");                     //审核状态
        $main_id = I("post.main_id");                   //主单id
        $explanation = I("post.explanation");           //审核批注
        $user_id = session("USER_ID");                  //审核用户

        if(!empty($status) && is_numeric($status) && !empty($main_id) && !empty($user_id))
        {
            $status = $this->getStatus($status);            //审核状态
            $sData = array(
                'USER_ID' => $user_id,
                'RESULT' => $status,
                'LAST_AUDIT_TIME' => date("Y-m-d H:i:s"),
                'EXPLANATION' => $explanation
            );
            $ret = M("SERVICE_T_RECEIPT_AUDIT")->where(array("RECEIPT_MAIN_ID"=>$main_id))->save($sData);

            if($ret)
            {
                backResult(1, "success");
            }
            else
            {
                backResult(0, L("_LOSS_VERIFY_ERROR_"));
            }
        }
    }
}