<?php
/**
 * 新品入库管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class NewProductController extends CommonController {

    /*新品入库*/
    public function new_product()
    {
        $washing_id = I("param.washing_id");                    //洗涤厂
        $start_time = I("param.start_time");                    //开始时间
        $end_time = I("param.end_time");                        //结束时间

        $list = array();
        if(!empty($washing_id) && !empty($start_time) && !empty($end_time))
        {
            $mWhere = array(
                'a.RECEIVER_ID' => $washing_id,
                'a.SENDER_ID' => 0,
                'a.CREATE_TIME' => array(array("GT", $start_time),array("LT", $end_time))
            );
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.CREATE_TIME,b.WAREHOUSE_NAME,b.WAREHOUSE_CONTACT_PERSON,c.USER_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON a.RECEIVER_ID=b.WAREHOUSE_ID")
                ->join("LEFT JOIN SERVICE_T_USER AS c ON a.USER_ID=c.USER_ID")
                ->where($mWhere)
                ->select();
            foreach($list as $key=>$li)
            {
                $list[$key]['TYPE'] = L("_ADD_NEWPRODUCT_");
            }
        }
        $this->assign("list", $list);
        $this->assign("title", L("_ADD_NEWPRODUCT_"));
        $this->display("new_product");
    }

    /**
     * 获取洗涤厂
     */
    public function get_washinglist()
    {
        $washing_name = I("post.washing_name");                         //要搜索洗涤厂名称
        $wWhere = array();

        if(!empty($washing_name))
        {
            $wWhere['a.WAREHOUSE_NAME'] = $washing_name;
        }
        $wWhere['b.PROP_NAME'] = C("WASHINGPLANTS");

        $washing_list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where($wWhere)
            ->select();

        backResult(1, "success", $washing_list);
    }

    /*新品入库详情*/
    public function new_product_details()
    {
        $main_id = I("param.lid");                              //单号

        $list = array();
        if(!empty($main_id))
        {
            $list = M("SERVICE_T_RECEIPT_MAIN")->alias("a")
                ->field("a.RECEIPT_MAIN_ID,a.CREATE_TIME,b.WAREHOUSE_NAME,b.WAREHOUSE_CONTACT_PERSON,c.RECEIPT_SUB_ID,c.LINEN_COUNT,d.LINEN_NAME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_ID AS b ON a.RECEIVER_ID=b.WAREHOUSE_ID")
                ->join("LEFT JOIN SERVICE_T_RECEIPT_SUB AS c ON a.RECEIPT_MAIN_ID=c.RECEIPT_MAIN_ID")
                ->join("LEFT JOIN SERVICE_T_LINEN AS d ON c.LINEN_ID=d.LINEN_ID")
                //->join("LEFT JOIN SERVICE_T_USER AS c ON a.USER_ID=c.USER_ID")
                ->where(array("a.RECEIPT_MAIN_ID"=>$main_id))
                ->select();
        }
        $this->assign("list", $list[0]);
        $this->assign("title", L("_NEWPRODUCT_DETAIL_"));
        $this->display("new_product_details");
    }

    /**
     * 入库单详情的详情
     */
    public function newproduct_detail_detail()
    {
        $sub_id = I("param.sub_id");                //子单id
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