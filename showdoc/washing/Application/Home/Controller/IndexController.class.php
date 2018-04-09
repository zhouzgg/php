<?php
/**
 * 首页
 */
namespace Home\Controller;
use Think\Controller;

class IndexController extends CommonController {

    /*首页*/
    public function index()
    {
        //仓库列表
        $storelist = M("SERVICE_T_WAREHOUSE_ID")->alias("a")
            ->field("a.WAREHOUSE_ID,a.WAREHOUSE_NAME")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS b ON b.WAREHOUSE_ID=a.WAREHOUSE_ID")
            ->where(array("b.PROP_NAME"=>C("STOREHOUSE")))
            ->select();

        //货物总数
        $allCount = M("SERVICE_T_RECEIPT_MAIN")->field("SUM(LINEN_COUNT) as allcount")->find();

        //待审核报损订单数
        $authBreakCount = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
            ->field("a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
            ->where(array("c.PROP_NAME"=>'报损库',"a.RESULT"=>-1))
            ->count();

        //丟失总件数
        $lostSum = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
            ->field("SUM(b.LINEN_COUNT) AS lostsum")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
            ->where(array('c.PROP_NAME'=>'丢失库'))
            ->find();

        //损坏总件数
        $breakSum = M("SERVICE_T_RECEIPT_AUDIT")->alias("a")
            ->field("SUM(b.LINEN_COUNT) AS breaksum")
            ->join("LEFT JOIN SERVICE_T_RECEIPT_MAIN AS b ON b.RECEIPT_MAIN_ID=a.RECEIPT_MAIN_ID")
            ->join("LEFT JOIN SERVICE_T_WAREHOUSE_PROPERTY AS c ON c.WAREHOUSE_ID=b.RECEIVER_ID")
            ->where(array('c.PROP_NAME'=>'报损库'))
            ->find();

        $allCount = empty($allCount['allcount']) ? 0 : intval($allCount['allcount']);
        $lostCount = empty($lostSum['lostsum']) ? 0 : $lostSum['lostsum'];
        $breakCount = empty($breakSum['breaksum']) ? 0 : $breakSum['breaksum'];
        //当前有效库存
        $lastcount = $allCount - ($lostCount + $breakCount);

        $this->assign("storelist", $storelist);
        $this->assign("allcount", $allCount);
        $this->assign("lostsum", $lostCount);
        $this->assign("breaksum", $breakCount);
        $this->assign("lastcount", $lastcount);
        $this->assign("authBreakCount", $authBreakCount);
        $this->assign("title", L("_INDEX_"));
        $this->display("index");
    }
}