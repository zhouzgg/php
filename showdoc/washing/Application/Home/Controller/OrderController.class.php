<?php
/**
 * 订单管理
 */
namespace Home\Controller;
use Home\Model\CustomerModel;
use Home\Model\DepartmentModel;
use Home\Model\StoreHouseModel;
use Home\Model\WarehouseModel;
use Home\Model\WashingplantsModel;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class OrderController extends CommonController {

    /*选择订单部门*/
    public function choose_clean()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();
        //库房
        //$storehouseModel = new StoreHouseModel();
        //$storehouse_list = $storehouseModel->getAllStoreHouse();
        //洗涤厂
        $washingModel = new WashingplantsModel();
        $washing_list = $washingModel->getAllWashing();

        $this->assign("customer_list", $customer_list);
        $this->assign("washing_list", $washing_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("choose");
    }

    /*选择订单部门*/
    public function choose_dirty()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();
        //库房
        //$storehouseModel = new StoreHouseModel();
        //$storehouse_list = $storehouseModel->getAllStoreHouse();
        //洗涤厂
        $washingModel = new WashingplantsModel();
        $washing_list = $washingModel->getAllWashing();

        $this->assign("customer_list", $customer_list);
        $this->assign("washing_list", $washing_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("choose");
    }

    /**
     * 获取库房
     */
    public function get_storehouse_list()
    {
        $customer_id = I("param.customer_id");              //客户id
        $department_id = I("param.department_id");            //部门id
        //库房
        $storehouseModel = new StoreHouseModel();
        $storehouse_list = $storehouseModel->getSomeStoreHouse($customer_id, $department_id);

        backResult(1, "success", $storehouse_list);
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
     * 根据客户和部门查找库房
     */
    public function get_storehouse_bytcdp()
    {
        $customer_id = I("param.customer_id");                  //客户id
        $department_id = I("param.department_id");              //部门id

        if(!empty($customer_id) && !empty($department_id) && is_numeric($customer_id) && is_numeric($department_id))
        {
            $cWhere['a.CUSTOMER_ID'] = $customer_id;                //客户

            $cWhere['a.DEPARTMENT_ID'] = $department_id;            //部门

            $cWhere['b.PROP_NAME'] = C("STOREHOUSE");               //库房属性

            $list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
                ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
                ->where($cWhere)
                ->select();
        }
        backResult(1, "success", $list);
    }

    /*净布草出库单*/
    public function clean_order()
    {
        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $storehouse_id = I("param.storehouse_id");                          //库房id
        $washing_id = I("param.washing_id");                                //洗涤厂id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();$list = array();
        if(!empty($customer_id) && !empty($department_id) && !empty($storehouse_id) && !empty($washing_id) && !empty($start_time) && !empty($end_time))
        {
            $wareModel = new WarehouseModel();
            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();
            //$washingModel = new WashingplantsModel();
            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);
            //库房
            $storehouseArray = $wareModel->getSomeHouse($storehouse_id);
            //洗涤厂
            //$washingArray = $washingModel->getSingleWashing($washing_id);

            $departmentData = "";
            $storehouseData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }
            foreach($storehouseArray as $store)
            {
                $storehouseData .= $store['WAREHOUSE_NAME'].",";
            }
            $departmentData = rtrim($departmentData, ",");
            $storehouseData = rtrim($storehouseData, ",");
            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'storehouse' => $storehouseData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            $mWhere = array(
                'a.SENDER_ID' => $washing_id,
                'a.RECEIVER_ID' => array("IN", $storehouse_id),
                'a.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time))
            );
            //列表
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.READER_ID,a.USER_ID,a.LINEN_COUNT,a.CREATE_TIME,b.WAREHOUSE_NAME,c.USER_NAME,d.WAREHOUSE_NAME AS CUSTOMER_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON b.WAREHOUSE_ID=a.SENDER_ID")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS d ON d.WAREHOUSE_ID=a.RECEIVER_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS c ON c.USER_ID=a.USER_ID")
                ->where($mWhere)
                ->select();

//            foreach($list as $key=>$li)
//            {
//                $list[$key]['SENDER'] = $storehouseArray['WAREHOUSE_NAME'];
//            }
        }
        $this->assign('url',U('Order/export_dirty',array('customer_id'=>$customer_id,'department_id'=>$department_id,'storehouse_id'=>$storehouse_id,'washing_id'=>$washing_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'clean')));
        $this->assign("other", $otherArray);
        $this->assign("list", $list);
        $this->assign("title", L("_ORDER_CLEAN_BY_USE_"));
        $this->display("clean_order");
    }


    /*净布草使用单详情*/
    public function clean_order_details()
    {
        $receipt_main_id = I("param.lib");                          //主单号
        $childList = array();
        $all_count = 0;
        if(!empty($receipt_main_id))
        {
            //根据主单号查找子单数据
            $childList = M("SERVICE_T_RECEIPT_SUB")->alias("a")
                ->field("a.RECEIPT_SUB_ID,a.LINEN_ID,a.LINEN_COUNT,b.LINEN_NAME")
                ->join("LEFT JOIN SERVICE_T_LINEN AS b ON b.LINEN_ID=a.LINEN_ID")
                ->where(array("a.RECEIPT_MAIN_ID"=>$receipt_main_id))
                ->select();
            foreach($childList as $list)
            {
                if(is_numeric($list['LINEN_COUNT']))
                {
                    $all_count += intval($list['LINEN_COUNT']);
                }
            }
        }
        $this->assign("childList", $childList);
        $this->assign("main_id", $receipt_main_id);
        $this->assign("all_count", $all_count);
        $this->assign("title", L("_ORDER_CLEAN_BY_USE_DETAIL_"));
        $this->display("clean_order_details");
    }

    /*脏布草出库单*/
    public function dirty_order()
    {
        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $storehouse_id = I("param.storehouse_id");                          //库房id
        $washing_id = I("param.washing_id");                                //洗涤厂id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $list = array();
        if(!empty($customer_id) && !empty($department_id) && !empty($storehouse_id) && !empty($washing_id)&& !empty($start_time) && !empty($end_time))
        {
            $wareModel = new WarehouseModel();
            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();
            $washingModel = new WashingplantsModel();
            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);
            //库房
            $storehouseArray = $wareModel->getSomeHouse($storehouse_id);
            //洗涤厂
            $washingArray = $washingModel->getSingleWashing($washing_id);

            $departmentData = "";           //部门
            $storehouseData = "";           //仓库
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }
            $departmentData = rtrim($departmentData, ",");
            foreach($storehouseArray as $store)
            {
                $storehouseData .= $store['WAREHOUSE_NAME'].",";
            }
            $storehouseData = rtrim($storehouseData, ",");
            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'storehouse' => $storehouseData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            $mWhere = array(
                'a.SENDER_ID' => array("IN", $storehouse_id),
                'a.RECEIVER_ID' => $washing_id,
                'a.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time))
            );
            //列表
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.READER_ID,a.USER_ID,a.LINEN_COUNT,a.CREATE_TIME,b.WAREHOUSE_NAME,c.USER_NAME,d.WAREHOUSE_NAME AS STOREHOUSE_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON b.WAREHOUSE_ID=a.RECEIVER_ID")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS d ON d.WAREHOUSE_ID=a.SENDER_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS c ON c.USER_ID=a.USER_ID")
                ->where($mWhere)
                ->select();

//            foreach($list as $key=>$li)
//            {
//                $list[$key]['SENDER'] = $washingArray['WAREHOUSE_NAME'];
//            }
        }
        $this->assign('url',U('Order/export_dirty',array('customer_id'=>$customer_id,'department_id'=>$department_id,'storehouse_id'=>$storehouse_id,'washing_id'=>$washing_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'dirty')));
        $this->assign("other", $otherArray);
        $this->assign("list", $list);
        $this->assign("title", L("_ORDER_DIRTY_BY_USE_"));
        $this->display("dirty_order");
    }

    public function export_dirty(){

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $storehouse_id = I("param.storehouse_id");                          //库房id
        $washing_id = I("param.washing_id");                                //洗涤厂id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $type = I("param.type");                                    //类型
        $otherArray = array();
        $list = array();
        if(!empty($customer_id) && !empty($department_id) && !empty($storehouse_id) && !empty($washing_id)&& !empty($start_time) && !empty($end_time)) {
            $wareModel = new WarehouseModel();
            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();
            $washingModel = new WashingplantsModel();
            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);
            //库房
            $storehouseArray = $wareModel->getSomeHouse($storehouse_id);
            //洗涤厂
            $washingArray = $washingModel->getSingleWashing($washing_id);

            $departmentData = "";           //部门
            $storehouseData = "";           //仓库
            foreach ($departmentArray as $depart) {
                $departmentData .= $depart['DEPARTMENT_NAME'] . ",";
            }
            $departmentData = rtrim($departmentData, ",");
            foreach ($storehouseArray as $store) {
                $storehouseData .= $store['WAREHOUSE_NAME'] . ",";
            }
            $storehouseData = rtrim($storehouseData, ",");
            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'storehouse' => $storehouseData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            if($type == 'dirty'){
                $mWhere = array(
                    'a.SENDER_ID' => array("IN", $storehouse_id),
                    'a.RECEIVER_ID' => $washing_id,
                    'a.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time))
                );
            }elseif($type == 'clean'){
                $mWhere = array(
                    'a.SENDER_ID' => $washing_id,
                    'a.RECEIVER_ID' => array("IN", $storehouse_id),
                    'a.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time))
                );
            }

            //列表
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.READER_ID,a.USER_ID,a.LINEN_COUNT,a.CREATE_TIME,b.WAREHOUSE_NAME,c.USER_NAME,d.WAREHOUSE_NAME AS STOREHOUSE_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON b.WAREHOUSE_ID=a.RECEIVER_ID")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS d ON d.WAREHOUSE_ID=a.SENDER_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS c ON c.USER_ID=a.USER_ID")
                ->where($mWhere)
                ->select();
        }

        $header = array(L('_ORDER_NO_'),L('_ORDER_REQUEST_TIME_'),L('_ORDER_SEND_'),L('_ORDER_REQUEST_'),L('_ORDER_REIVER_'),L('_ORDER_REQUEST_DEVICE_'));
        $field_list = array('RECEIPT_MAIN_ID','CREATE_TIME','STOREHOUSE_NAME','WAREHOUSE_NAME','USER_NAME','READER_ID');
        $title = L("_ORDER_DIRTY_BY_USE_");
        $this->export_washing_order($list,$header,$field_list,$otherArray,$title);

    }


    /**
     * 导出Excel数据
     * @param $list 数据
     * @param $header 头部标题
     * @param $field_list 字段
     * @param $otherArray 其他数据
     * @param $title 导出标题
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function export_washing_order($list,$header,$field_list,$otherArray,$title){

        require_once LIB_PATH.'Vendor/Excel/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1',L('_ORDER_CUSTOMER_')."：$otherArray[customer]")
            ->setCellValue('A2',L('_ORDER_TIME_')."：$otherArray[start_time]~$otherArray[end_time]")
            ->setCellValue('E1',L('_ORDER_STOREHOUSE_')."：$otherArray[storehouse]")
            ->setCellValue('C1',L('_ORDER_DEPARTMENT_')."：$otherArray[department]");

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $j = 0;
        foreach ($header as $k=>$v){

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].'4',$v);

        }

        $objPHPExcel->getActiveSheet()->mergeCells('A1:B1');
        $objPHPExcel->getActiveSheet()->mergeCells('C1:D1');
        $objPHPExcel->getActiveSheet()->mergeCells('E1:'.$cellName[$j-1].'1');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cellName[$j-1].'3');

        $i = 4;
        foreach ($list as $k=>$v){

            $j = 0;
            $i++;
            foreach ($field_list as $key=>$val) {

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].$i,$v[$val]);

            }

        }

        $write = new \PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="'.$title.'_'.date('YmdHi').'.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');

    }

    /*脏布草洗涤详情*/
    public function dirty_order_details()
    {
        $receipt_main_id = I("param.lid");                          //主单号
        $childList = array();
        $all_count = 0;
        if(!empty($receipt_main_id))
        {
            //根据主单号查找子单数据
            $childList = M("SERVICE_T_RECEIPT_SUB")->alias("a")
                ->field("a.RECEIPT_SUB_ID,a.LINEN_ID,a.LINEN_COUNT,b.LINEN_NAME")
                ->join("LEFT JOIN SERVICE_T_LINEN AS b ON b.LINEN_ID=a.LINEN_ID")
                ->where(array("a.RECEIPT_MAIN_ID"=>$receipt_main_id))
                ->select();
            foreach($childList as $list)
            {
                if(is_numeric($list['LINEN_COUNT']))
                {
                    $all_count += intval($list['LINEN_COUNT']);
                }
            }
        }
        $this->assign("childList", $childList);
        $this->assign("main_id", $receipt_main_id);
        $this->assign("all_count", $all_count);
        $this->assign("title", L("_ORDER_DIRTY_BY_USE_DETAIL_"));
        $this->display("dirty_order_details");
    }

    /**
     * 脏布草/净布草洗涤详情的详情
     */
    public function dirty_order_detail_details()
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

     /*选择订单部门*/
    public function choose_linen()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("choose_linen");
    }

    /*选择订单部门*/
    public function sender_choose_linen()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("choose_linen");
    }

    /*选择订单部门*/
    public function recriver_choose_linen()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("choose_linen");
    }

    /*选择订单部门*/
    public function day_choose_linen()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("day_choose_linen");
    }

    /*选择订单部门*/
    public function day_sender_choose_linen()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("day_choose_linen");
    }

    /*选择订单部门*/
    public function day_recriver_choose_linen()
    {
        //客户
        $customerModel = new CustomerModel();
        $customer_list = $customerModel->getAllCustomer();

        $this->assign("customer_list", $customer_list);
        $this->assign("title", L("_ORDER_SELECT_ORDER_DEPARTMENT_"));
        $this->display("day_choose_linen");
    }

    /*月度出库明细表*/
    public function sender_linen_month_order()
    {

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $i = 0;
        $DateArr = array();

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
                'm.SENDER_ID'=>array('IN',$warehouseid)
            );

            //列表
            $list = $this->get_month_order_data($mWhere);

            $DateArr = $this->get_date_list($start_time,$end_time);
            $i = count($DateArr);

            //处理数据格式
            $data = $this->deal_linen_order_data($list,$DateArr);

        }

        $this->assign("url",U('order/exprort_order',array('customer_id'=>$customer_id,'department_id'=>$department_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'sender')));
        $this->assign("other", $otherArray);
        $this->assign("list", $data);
        $this->assign("date", $DateArr);
        $this->assign("i", $i);
        $this->assign("title", L("_ORDER_LINEN_DETAIL_LIST_SENDER_"));
        $this->display("linen_month_order");
    }

    /*月度入库明细表*/
    public function recriver_linen_month_order()
    {

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $DateArr = array();
        $i = 0;

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
                'm.RECEIVER_ID'=>array('IN',$warehouseid)
            );

            //列表
            $list = $this->get_month_order_data($mWhere);

            $DateArr = $this->get_date_list($start_time,$end_time);
            $i = count($DateArr);

            //处理数据格式
            $data = $this->deal_linen_order_data($list,$DateArr);

        }

        $this->assign("url",U('order/exprort_order',array('customer_id'=>$customer_id,'department_id'=>$department_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'receiver')));
        $this->assign("other", $otherArray);
        $this->assign("list", $data);
        $this->assign("date", $DateArr);
        $this->assign("i", $i);
        $this->assign("title", L("_ORDER_LINEN_DETAIL_LIST_RECRIVER_"));
        $this->display("linen_month_order");
    }

    /*月度总出入明细表*/
    public function detail_linen_month_order()
    {

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $DateArr = array();
        $i = 0;

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
                '_complex'=>array(
                    'm.SENDER_ID'=>array('IN',$warehouseid),
                    'm.RECEIVER_ID'=>array('IN',$warehouseid),
                    '_logic'=>'or'
                )
            );

            //列表
            $list = $this->get_month_order_data_s($mWhere);

            $DateArr = $this->get_date_list($start_time,$end_time);
            $i = count($DateArr);

            //处理数据格式
            $data = $this->deal_linen_order_data_s($list,$DateArr,$warehouseid);

        }

        $this->assign("url",U('order/exprort_order',array('customer_id'=>$customer_id,'department_id'=>$department_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'detail')));
        $this->assign("other", $otherArray);
        $this->assign("list", $data);
        $this->assign("date", $DateArr);
        $this->assign("i", $i);
        $this->assign("title", L("_ORDER_LINEN_DETAIL_LIST_"));
        $this->display("linen_order");
    }

    /*日出库明细表*/
    public function day_sender_linen_month_order()
    {

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $i = 0;
        $DateArr = array();

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
                'm.SENDER_ID'=>array('IN',$warehouseid)
            );

            //列表
            $list = $this->get_month_order_data($mWhere);
            $DateArr = $this->get_date_list($start_time,$end_time);
            $i = count($DateArr);

            //处理数据格式
            $data = $this->deal_linen_order_data($list,$DateArr);

        }

        $this->assign("url",U('order/exprort_order',array('customer_id'=>$customer_id,'department_id'=>$department_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'sender')));
        $this->assign("other", $otherArray);
        $this->assign("list", $data);
        $this->assign("date", $DateArr);
        $this->assign("i", $i);
        $this->assign("title", L("_DAY_ORDER_LINEN_DETAIL_LIST_SENDER_"));
        $this->display("linen_month_order");
    }

    /*日入库明细表*/
    public function day_recriver_linen_month_order()
    {

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $DateArr = array();
        $i = 0;

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
                'm.RECEIVER_ID'=>array('IN',$warehouseid)
            );

            //列表
            $list = $this->get_month_order_data($mWhere);

            $DateArr = $this->get_date_list($start_time,$end_time);
            $i = count($DateArr);

            //处理数据格式
            $data = $this->deal_linen_order_data($list,$DateArr);

        }

        $this->assign("url",U('order/exprort_order',array('customer_id'=>$customer_id,'department_id'=>$department_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'receiver')));
        $this->assign("other", $otherArray);
        $this->assign("list", $data);
        $this->assign("date", $DateArr);
        $this->assign("i", $i);
        $this->assign("title", L("_DAY_ORDER_LINEN_DETAIL_LIST_RECRIVER_"));
        $this->display("linen_month_order");
    }

    /*日总出入明细表*/
    public function day_detail_linen_month_order()
    {

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $DateArr = array();
        $i = 0;

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
                '_complex'=>array(
                    'm.SENDER_ID'=>array('IN',$warehouseid),
                    'm.RECEIVER_ID'=>array('IN',$warehouseid),
                    '_logic'=>'or'
                )
            );

            //列表
            $list = $this->get_month_order_data_s($mWhere);

            $DateArr = $this->get_date_list($start_time,$end_time);
            $i = count($DateArr);

            //处理数据格式
            $data = $this->deal_linen_order_data_s($list,$DateArr,$warehouseid);

        }

        $this->assign("url",U('order/exprort_order',array('customer_id'=>$customer_id,'department_id'=>$department_id,'start_time'=>$start_time,'end_time'=>$end_time,'type'=>'detail')));
        $this->assign("other", $otherArray);
        $this->assign("list", $data);
        $this->assign("date", $DateArr);
        $this->assign("i", $i);
        $this->assign("title", L("_DAY_ORDER_LINEN_DETAIL_LIST_"));
        $this->display("linen_order");
    }

    //获取开始时间到结束时间中间的年月日
    public function get_date_list($start_time,$end_time){

        $t = true;
        $i = 0;
        $max_day = 30;
        $DateArr = array();
        while($t){

            $current_date = date('Y-m-d',strtotime($start_time)+24*3600*$i);
            $i++;
            array_push($DateArr, $current_date);
            if(strtotime($current_date) == strtotime($end_time) || $i >= $max_day || strtotime($current_date) > strtotime($end_time)){
                $t = false;
            }

        }

        return $DateArr;
    }

    public function get_order_date_list($list){

        $date = array();
        foreach ($list as $k=>$v){

            $date [] = $v['DATE'];

        }

        $data = array_unique($date);
        sort($data);

        return $data;

    }

    /**
     * 处理月度的数据格式
     * @param $list 月度出入库数据
     * @param $DateArr 时间段年月日
     */
    public function deal_linen_order_data($list,$DateArr){

        $data = array();
        foreach ($list as $key => $value) {

            $data[$value['LINEN_NAME']][$value['DATE']] = $value['COUNT'];

            foreach ($DateArr as $k => $val) {

                if(empty($data[$value['LINEN_NAME']][$val])){
                    $data[$value['LINEN_NAME']][$val] = 0;
                }

            }

            $data[$value['LINEN_NAME']]['合计']+=$value['COUNT'];
        }

        return $data;
    }

    /**
     *获取库房ID
     * @param $customer_id 客户ID
     * @param $department_id 部门ID
     */
    public function get_warehouse_ids($customer_id,$department_id){

        $warehouseid = M('SERVICE_T_WAREHOUSE_ID')
            ->field("WAREHOUSE_ID")
            ->where(array('CUSTOMER_ID'=>$customer_id,'DEPARTMENT_ID'=>array('IN',$department_id)))
            ->select();

        if(empty($warehouseid)){
            $warehouseid = '';
        }else{
            foreach ($warehouseid as $k=>$v){
                $data[] = $v['WAREHOUSE_ID'];
            }
            $warehouseid = $data;
        }
        return $warehouseid;
    }

    //获取月度出入报表
    public function get_month_order_data($where){

        $list = M("SERVICE_T_RECEIPT_SUB")->alias("s")
//            ->field("
//                    l.LINEN_ID,l.LINEN_NAME,DATE_FORMAT(m.CREATE_TIME,'%Y-%m-%d') as DATE,sum(s.LINEN_COUNT) as COUNT,m.SENDER_ID,m.RECEIVER_ID"
//            )
            ->field("
                    l.LINEN_ID,l.LINEN_NAME,CONVERT(varchar(100),m.CREATE_TIME,23) as DATE,sum(s.LINEN_COUNT) as COUNT,m.SENDER_ID,m.RECEIVER_ID"
            )
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS m ON s.RECEIPT_MAIN_ID = m.RECEIPT_MAIN_ID")
            ->join("RIGHT JOIN SERVICE_T_LINEN AS l ON l.LINEN_ID = s.LINEN_ID")
            ->where($where)
//            ->group("l.LINEN_ID,DATE_FORMAT(m.CREATE_TIME,'%Y-%m-%d')")
            ->group("l.LINEN_ID,CONVERT(varchar(100),m.CREATE_TIME,23),l.LINEN_NAME,m.SENDER_ID,m.RECEIVER_ID")
            ->select();

        return $list;

    }

    //获取月度出入报表
    public function get_month_order_data_s($where){

        $list = M("SERVICE_T_RECEIPT_SUB")->alias("s")
//            ->field("
//                    l.LINEN_ID,l.LINEN_NAME,DATE_FORMAT(m.CREATE_TIME,'%Y-%m-%d') as DATE,sum(s.LINEN_COUNT) as COUNT,m.SENDER_ID,m.RECEIVER_ID"
//            )
            ->field("
                    l.LINEN_ID,l.LINEN_NAME,CONVERT(varchar(100),m.CREATE_TIME,23) as DATE,sum(s.LINEN_COUNT) as COUNT,m.SENDER_ID,m.RECEIVER_ID"
            )
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS m ON s.RECEIPT_MAIN_ID = m.RECEIPT_MAIN_ID")
            ->join("RIGHT JOIN SERVICE_T_LINEN AS l ON l.LINEN_ID = s.LINEN_ID")
            ->where($where)
//            ->group("l.LINEN_ID,DATE_FORMAT(m.CREATE_TIME,'%Y-%m-%d'),m.SENDER_ID,m.RECEIVER_ID")
            ->group("l.LINEN_ID,CONVERT(varchar(100),m.CREATE_TIME,23),l.LINEN_NAME,m.SENDER_ID,m.RECEIVER_ID")
            ->select();

        return $list;

    }

    /**
     * 处理月度的数据格式
     * @param $list 月度出入库数据
     * @param $DateArr 时间段年月日
     */
    public function deal_linen_order_data_s($list,$DateArr,$warehouseid){

        $data = array();
        foreach ($list as $key => $value) {

            if(in_array($value['SENDER_ID'],$warehouseid)){
                $data[$value['LINEN_NAME']][$value['DATE']]['SENDER_ID'] = $value['COUNT'];
                $data[$value['LINEN_NAME']]['合计']['SENDER_ID']+=$value['COUNT'];
            }elseif(in_array($value['RECEIVER_ID'],$warehouseid)){
                $data[$value['LINEN_NAME']][$value['DATE']]['RECEIVER_ID'] = $value['COUNT'];
                $data[$value['LINEN_NAME']]['合计']['RECEIVER_ID']+=$value['COUNT'];
            }

            if(empty($data[$value['LINEN_NAME']]['合计']['SENDER_ID'])){
                $data[$value['LINEN_NAME']]['合计']['SENDER_ID'] = 0;
            }
            if(empty($data[$value['LINEN_NAME']]['合计']['RECEIVER_ID'])){
                $data[$value['LINEN_NAME']]['合计']['RECEIVER_ID'] = 0;
            }


            foreach ($DateArr as $k => $val) {

                if(empty($data[$value['LINEN_NAME']][$val]['SENDER_ID'])){
                    $data[$value['LINEN_NAME']][$val]['SENDER_ID'] = 0;
                }

                if(empty($data[$value['LINEN_NAME']][$val]['RECEIVER_ID'])){
                    $data[$value['LINEN_NAME']][$val]['RECEIVER_ID'] = 0;
                }

            }

        }

        return $data;
    }

    /*
     * 导出报表订单
     */
    public function exprort_order(){

        $customer_id = I("param.customer_id");                              //客户id
        $department_id = I("param.department_id");                          //部门id
        $start_time = I("param.start_time");                                //开始时间
        $end_time = I("param.end_time");                                    //结束时间
        $otherArray = array();
        $DateArr = array();
        $i = 0;
        $type = I('param.type');

        if(!empty($customer_id) && !empty($department_id) && !empty($start_time) && !empty($end_time))
        {

            //记录每天时间
//            $DateArr = $this->get_date_list($start_time,$end_time);
//            $i = count($DateArr);

            $customerModel = new CustomerModel();
            $departmentModel = new DepartmentModel();

            //客户
            $customerArray = $customerModel->getSingleCustomer($customer_id);
            //部门
            $departmentArray = $departmentModel->getSingleDepartment($department_id);

            $departmentData = "";
            foreach($departmentArray as $depart)
            {
                $departmentData .= $depart['DEPARTMENT_NAME'].",";
            }

            $departmentData = rtrim($departmentData, ",");

            //其他数据
            $otherArray = array(
                'customer' => $customerArray['WAREHOUSE_NAME'],
                'department' => $departmentData,
                'start_time' => $start_time,
                'end_time' => $end_time
            );

            //客户和部门获取库房ID
            $warehouseid = $this->get_warehouse_ids($customer_id,$department_id);

            $mWhere = array(
                'm.CREATE_TIME' => array(array("GT", $start_time), array("LT", $end_time.' 23:59:59')),
            );

            $title = '月度汇总表';
            if($type == 'sender'){
                $mWhere['m.SENDER_ID'] = array('IN',$warehouseid);
                $title = '月度发净报表';
            }elseif($type == 'receiver'){
                $mWhere['m.RECEIVER_ID'] = array('IN',$warehouseid);
                $title = '月度收脏报表';
            }elseif($type == 'detail'){
                $mWhere['_complex'] = array(
                    'm.SENDER_ID'=>array('IN',$warehouseid),
                    'm.RECEIVER_ID'=>array('IN',$warehouseid),
                    '_logic'=>'or'
                );
                $title = '月度汇总表';
            }

            //列表


            //处理数据格式
            if($type == 'detail'){
                $list = $this->get_month_order_data_s($mWhere);

                $DateArr = $this->get_date_list($start_time,$end_time);

                $data = $this->deal_linen_order_data_s($list,$DateArr,$warehouseid);

                //"序号,名称,地址,联系人,联系方式"."\n";
                $this->export_excel_s($data,$DateArr,$otherArray,$title);
            }else{
                $list = $this->get_month_order_data($mWhere);

                $DateArr = $this->get_date_list($start_time,$end_time);

                $data = $this->deal_linen_order_data($list,$DateArr);

                //"序号,名称,地址,联系人,联系方式"."\n";
                $this->export_excel($data,$DateArr,$otherArray,$title);
            }


        }

    }

    /**
     * @param $list 数据
     * @param $header 头部标题
     * @param $otherArray 其他数据
     * @param $title 导出Excel标题
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function export_excel($list,$header,$otherArray,$title){

        require_once LIB_PATH.'Vendor/Excel/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1',L('_ORDER_CUSTOMER_')."：$otherArray[customer]")
            ->setCellValue('A2',L('_ORDER_TIME_')."：$otherArray[start_time]~$otherArray[end_time]")
            ->setCellValue('B1',L('_ORDER_DEPARTMENT_')."：$otherArray[department]");



        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $j = 1;
        $data = $list;
        foreach ($header as $k=>$v){

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].'4',$v);

        }
        $objPHPExcel->getActiveSheet()->mergeCells('B1:'.$cellName[$j].'1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:'.$cellName[$j].'2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cellName[$j].'3');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].'4','小计');

        $i = 4;
        foreach ($list as $k=>$v){

            $j = 1;
            $i++;
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,$k);

            foreach ($header as $key=>$val) {

                $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].$i,$v[$val]);

            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].$i,$v['合计']);
        }

        $write = new \PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="'.$title.'_'.date('YmdHi').'.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');

    }

    /**
     * @param $list 数据
     * @param $header 头部标题
     * @param $otherArray 其他数据
     * @param $title 导出Excel标题
     * @throws \PHPExcel_Exception
     * @throws \PHPExcel_Writer_Exception
     */
    public function export_excel_s($list,$header,$otherArray,$title){

        require_once LIB_PATH.'Vendor/Excel/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1',L('_ORDER_CUSTOMER_')."：$otherArray[customer]")
            ->setCellValue('A2',L('_ORDER_TIME_')."：$otherArray[start_time]~$otherArray[end_time]")
            ->setCellValue('B1',L('_ORDER_DEPARTMENT_')."：$otherArray[department]");

        $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');
        $j = 1;

        foreach ($header as $k=>$v){

            if($j%2 == 0){
                $objPHPExcel->getActiveSheet()->mergeCells($cellName[$j-1].'4:'.$cellName[$j].'4');
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].'4',$v);
            $j+=2;

        }
        $objPHPExcel->getActiveSheet()->mergeCells('B1:'.$cellName[$j].'1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:'.$cellName[$j].'2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:'.$cellName[$j].'3');

        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j++].'4','小计');
        $objPHPExcel->getActiveSheet()->mergeCells($cellName[$j-1].'4:'.$cellName[$j].'4');

        $i = 5;
        foreach ($list as $k=>$v){

            if($i%2 == 1){
                $t = $i+1;
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':A'.$t);
            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i,$k);

            $j = 1;
            foreach ($header as $key=>$val) {

                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].$i,'净');
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].$t,$v[$val]['SENDER_ID']);
                    $y = $j+1;
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$y].$i,'污');
                    $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$y].$t,$v[$val]['RECEIVER_ID']);
                    $j +=2;

            }

            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].$i,'净');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].$t,$v['合计']['SENDER_ID']);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[++$j].$i,'污');
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$j].$t,$v['合计']['RECEIVER_ID']);
            $i = $i+2;

        }

        $write = new \PHPExcel_Writer_Excel5($objPHPExcel);
        header("Pragma: public");
        header("Expires: 0");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");;
        header('Content-Disposition:attachment;filename="'.$title.'_'.date('YmdHi').'.xls"');
        header("Content-Transfer-Encoding:binary");
        $write->save('php://output');

    }
}