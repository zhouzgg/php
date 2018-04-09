<?php
/**
 * 洗涤厂管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class WashingPlantsController extends CommonController {

    //private $PROPERTY = "WASHINGPLANTS";                         //库房表对应的属性名称

    /*新增洗涤厂*/
    public function add_washing_plant(){
        $this->assign("title", L("_ADD_WASHING_PLANTS_"));
        $this->display("add_washing_plant");
    }

    /*提交新增洗涤厂*/
    public function post_washing_plant()
    {
        $washing_name = I("post.washing_name");                                     //洗涤厂名称
        $washing_address = I("post.washing_address");                              //洗涤厂地址
        $washing_contact_person = I("post.washing_contact_person");               //联系人
        $washing_contact_phone = I("post.washing_contact_phone");                  //联系方式
        $washing_logo = I("post.washing_logo");                                     //logo地址

        if(empty($washing_name))
        {
            backResult(0, L("_NOT_WASHING_NAME_"));
        }
        if(empty($washing_address))
        {
            backResult(0, L("_NOT_WASHING_ADDRESS_"));
        }
        if(empty($washing_contact_person))
        {
            backResult(0, L("_NOT_WASHING_CONTACT_PERSON_"));
        }
        if(empty($washing_contact_phone))
        {
            backResult(0, L("_NOT_WASHING_CONTACT_PHONE_"));
        }
        if(empty($washing_logo))
        {
            backResult(0, L("_NOT_WASHING_LOGO_"));
        }
        $wData = array(
            'WAREHOUSE_NAME' => $washing_name,
            'WAREHOUSE_ADDRESS' => $washing_address,
            'WAREHOUSE_CONTACT_PERSON' => $washing_contact_person,
            'WAREHOUSE_CONTACT_PHONE' => $washing_contact_phone,
            'WAREHOUSE_LOGO' => $washing_logo,
            'REG_TIME' => date("Y-m-d H:i:s")
        );
        //添加（ps:这里的表名必须是小写）
        $ret = M("SERVICE_T_WAREHOUSE_ID")->data($wData)->add();
        if($ret)
        {
            //添加属性
            $pData = array(
                'WAREHOUSE_ID' => $ret,
                'PROP_NAME' => C("WASHINGPLANTS"),
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

    /*洗涤厂列表*/
    public function washing_plant_list()
    {
        $list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME,a.WAREHOUSE_ADDRESS,a.WAREHOUSE_CONTACT_PERSON,a.WAREHOUSE_CONTACT_PHONE,a.WAREHOUSE_LOGO")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where(array("b.PROP_NAME"=>C("WASHINGPLANTS")))
            ->select();

        $this->assign("list", $list);
        $this->assign("title", L("_WASHING_PLANTS_LIST_"));
        $this->display("washing_plant_list");
    }

    /*导出洗涤厂excel*/
    public function export_washing_plant()
    {
        $list = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME,a.WAREHOUSE_ADDRESS,a.WAREHOUSE_CONTACT_PERSON,a.WAREHOUSE_CONTACT_PHONE,a.WAREHOUSE_LOGO")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
            ->where(array("b.PROP_NAME"=>C("WASHINGPLANTS")))
            ->select();

        //"编号,名称,地址,联系人,联系方式"."\n";
        $header = L('_WASHING_NO_').",".L('_WASHING_NAME_').",".L('_WASHING_ADDRESS_').",".L('_WASHING_PERSON_').",".L('_WASHING_PHONE_')."\n";

        $str = iconv('utf-8','GBK', $header);
        foreach($list as $li)
        {
            $washing_id = $li['WAREHOUSE_ID'];
            $washing_name = iconv('utf-8','gb2312', $li['WAREHOUSE_NAME']);                                     //洗涤厂名称
            $washing_address = iconv('utf-8','gb2312', $li['WAREHOUSE_ADDRESS']);                              //洗涤厂地址
            $washing_contact_person = iconv('utf-8','gb2312', $li['WAREHOUSE_CONTACT_PERSON']);              //联系人
            $washing_contact_phone = iconv('utf-8','gb2312', $li['WAREHOUSE_CONTACT_PHONE']);                  //联系方式

            $str .= $washing_id . "," . $washing_name . "," . $washing_address . "," . $washing_contact_person . "," . $washing_contact_phone."\t"  . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_WASHING_PLANTS_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }

    /*洗涤厂列表详情*/
    public function washing_plant_list_details()
    {
        $lid = I("get.lid");                                            //洗涤厂id
        $order_no = I("get.no");                                        //订单编号
        $start_time = I("get.start");                                   //开始时间
        $end_time = I("get.end");                                       //结束时间

        //验证
        if(!empty($lid) && is_numeric($lid))
        {
            //洗涤厂信息
            $washingDetail = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
                ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME,a.WAREHOUSE_ADDRESS,a.WAREHOUSE_CONTACT_PERSON,a.WAREHOUSE_CONTACT_PHONE,a.WAREHOUSE_LOGO,a.REG_TIME")
                ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON a.WAREHOUSE_ID=b.WAREHOUSE_ID")
                ->where(array("b.PROP_NAME"=>C("WASHINGPLANTS"), "a.WAREHOUSE_ID"=>$lid))
                ->find();

            if(!empty($washingDetail) && !empty($order_no) && !empty($start_time) && !empty($end_time))
            {
                $detailList = M("SERVICE_T_RECEIPT_MAIN")
                    ->field("RECEIPT_MAIN_ID,LINEN_COUNT,CREATE_TIME")
                    ->where(array("RECEIPT_MAIN_ID"=>$order_no, "CREATE_TIME"=>array(array("GT", $start_time),array("LT", $end_time))))
                    ->select();
            }
        }
        $this->assign("washingDetail", $washingDetail);
        $this->assign("detailList", $detailList);
        $this->assign("title", L("_WASHING_PLANTS_LIST_DETAIL_"));
        $this->display("washing_plant_list_details");
    }

    /**
     * 洗涤厂列表详情导出
     */
    public function export_washing_detail()
    {
        $order_no = I("get.no");                                        //订单编号
        $start_time = I("get.start");                                   //开始时间
        $end_time = I("get.end");                                       //结束时间

        if(!empty($washingDetail) && !empty($order_no) && !empty($start_time) && !empty($end_time))
        {
            $detailList = M("SERVICE_T_RECEIPT_MAIN")
                ->field("RECEIPT_MAIN_ID,LINEN_COUNT,CREATE_TIME")
                ->where(array("RECEIPT_MAIN_ID"=>$order_no, "CREATE_TIME"=>array(array("GT", $start_time),array("LT", $end_time))))
                ->select();

            $header = "订单编号,日期,数量,库存"."\n";

            $str = iconv('utf-8','gb2312', $header);

            foreach($detailList as $li)
            {
                $main_id = $li['RECEIPT_MAIN_ID'];                                               //订单编号
                $create_time = iconv('utf-8','gb2312', $li['CREATE_TIME']);                                     //日期
                $line_count = iconv('utf-8','gb2312', $li['LINEN_COUNT']);                              //数量

                $str .= $main_id . "," . $create_time. "\t" . "," . $line_count."\t"  . "\n";
            }
        }
        $filename = iconv('UTF-8','GBK', L("_WASHING_PLANTS_LIST_").'-'.time().'.csv');
        explode_csv($filename, $str);
    }
}