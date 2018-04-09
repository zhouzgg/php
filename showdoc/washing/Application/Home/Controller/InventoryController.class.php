<?php
/**
 * 库存盘存
 */
namespace Home\Controller;
use Home\Model\CustomerModel;
use Home\Model\DepartmentModel;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class InventoryController extends CommonController {

    /**
     * 选择部门
     */
    public function choose()
    {
        $customerModel = new CustomerModel();
        //客户
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title",L("_SELECT_DEPARTMENT_PAGE_"));
        $this->display("choose");
    }

    /**
     * 查找该客户对应的部门
     */
    public function get_department_list()
    {
        $customer_id = I("param.customer_id");              //客户id

        if(!empty($customer_id) && is_numeric($customer_id))
        {
            //调用部门数据
            $departMentModel = new DepartmentModel();
            $result = $departMentModel->getDepartment($customer_id);
        }
        backResult(1, "success", $result);
    }

    /**
     * 库存盘存
     */
    public function inventory()
    {
        $customer_id = I("param.customer_id");                          //客户id
        $department_id = I("param.department_id");                      //部门id

        if(!empty($customer_id) && !empty($department_id) && is_numeric($customer_id))
        {
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.READER_ID,a.USER_ID,a.LINEN_COUNT,a.CREATE_TIME,b.WAREHOUSE_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON b.WAREHOUSE_ID=a.SENDER_ID")
                ->where("a.SENDER_ID=a.RECEIVER_ID")
                ->where(array("a.SENDER_ID"=>$customer_id,"a.RECEIVER_ID"=>$customer_id))
                ->select();
        }
        $this->assign("list", $list);
        $this->assign("customer_id", $customer_id);
        $this->assign("department_id", $department_id);
        $this->assign("title", L("_INVENTORY_PAGE_"));
        $this->display("inventory");
    }

    /**
     * 库存盘存详情
     */
    public function inventory_details()
    {
        $receipt_main_id = I("param.lid");                          //主单号

        if(!empty($receipt_main_id))
        {
            //根据主单号查找子单数据
            $childList = M("SERVICE_T_RECEIPT_SUB")->alias("a")
                    ->field("a.RECEIPT_SUB_ID,a.LINEN_ID,a.LINEN_COUNT,b.LINEN_NAME")
                    ->join("LEFT JOIN SERVICE_T_LINEN AS b ON b.LINEN_ID=a.LINEN_ID")
                    ->where(array("a.RECEIPT_MAIN_ID"=>$receipt_main_id))
                    ->select();
        }
        $this->assign("childList", $childList);
        $this->assign("main_id", $receipt_main_id);
        $this->assign("title", L("_INVENTORY_DETAIL_PAGE_"));
        $this->display("inventory_details");
    }

    /**
     * 导出
     */
    public function export_inventory_list()
    {
        $customer_id = I("param.customer_id");                          //客户id
        $department_id = I("param.department_id");                      //部门id

        if(!empty($customer_id) && !empty($department_id) && is_numeric($customer_id))
        {
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.READER_ID,a.USER_ID,a.LINEN_COUNT,a.CREATE_TIME,b.WAREHOUSE_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON b.WAREHOUSE_ID=a.SENDER_ID")
                ->where("a.SENDER_ID=a.RECEIVER_ID")
                ->where(array("a.SENDER_ID"=>$customer_id,"a.RECEIVER_ID"=>$customer_id))
                ->select();
            if(!empty($list))
            {
                //$header = "盘点单号,日期,仓库,盘存数量"."\n";
                $header = L("_INVENTORY_NO_").",".L("_INVENTORY_DATA_").",".L("_INVENTORY_STOREHOUSE_").",".L("_INVENTORY_NUMBER_")."\n";

                $str = iconv('utf-8','GBK', $header);
                foreach($list as $li)
                {
                    $main_id = iconv('utf-8','GBK', $li['RECEIPT_MAIN_ID']);                                 //盘点单号
                    $create_time = iconv('utf-8','GBK', $li['CREATE_TIME']);                                 //日期
                    $warehouse_name = iconv('utf-8','GBK', $li['WAREHOUSE_NAME']);                           //仓库
                    $linen_count = iconv('utf-8','GBK', $li['LINEN_COUNT']);                              //盘存数量

                    $str .= $main_id ."\t". "," . $create_time ."\t". "," . $warehouse_name . "," . $linen_count  . "\n";
                }
            }
        }
        $filename = iconv('UTF-8','GBK', L("_INVENTORY_PAGE_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }

    /**
     * 盘存详情的详情
     */
    public function inventory_detail_detail()
    {
        $sub_id = I("param.sub_id");                //子单id
        $list = array();
        if(!empty($sub_id))
        {
            $list = M("SERVICE_T_RECEIPT_DETAIL")->alias("a")
                ->field("a.RECEIPT_SUB_ID,b.BARCODE")
                ->join("LEFT JOIN SERVICE_T_TAG AS b ON b.TAG_ID=a.TAG_ID")
                ->where(array("a.RECEIPT_SUB_ID"=>$sub_id))
                ->select();
        }
        backResult(1, "success", $list);
    }
}