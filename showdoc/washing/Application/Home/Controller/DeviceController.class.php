<?php
/**
 * 设备管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class DeviceController extends CommonController {

    /*新增设备*/
    public function add_device()
    {
        $userlist = M("SERVICE_T_USER")->field("USER_ID,USER_NAME,CELLPHONE")->select();

        $this->assign("userlist", $userlist);
        $this->assign("title", L("_DEVICE_ADD_"));
        $this->display("add_device");
    }

    /**
     * 添加/修改设备信息
     */
    public function post_device()
    {
        $device_name = I("post.device_name");                 //设备名称
        $device_id = I("post.device_id");                   //设备编号
        $user_id = I("post.user_id");                         //领用者
        //$device_phone = I("post.device_phone");              //领用者联系方式

        //设备名称
        if(empty($device_name))
        {
            backResult(0, L("_DEVICE_ADD_NOT_NAME_"));
        }
        //设备编号
        if(empty($device_id))
        {
            backResult(0, L("_DEVICE_ADD_NOT_NUMBER_"));
        }
        //领用者
        if(empty($user_id))
        {
            backResult(0, L("_DEVICE_ADD_NOT_USER_"));
        }
        //领用者联系方式
        //if(empty($device_phone))
        //{
        //    backResult(0, L("_DEVICE_ADD_NOT_PHONE_"));
        //}
        //添加
        $addData = array(
            'READER_ID' => $device_id,
            'USER_ID' => $user_id,
            'READER_NAME' => $device_name,
            'REG_TIME' => date("Y-m-d H:i:s")
        );
        $ret = M("SERVICE_T_READER")->data($addData)->add();
        if($ret)
        {
            backResult(1, "success");
        }
        else
        {
            backResult(0, L("_ADD_ERROR_"));
        }
    }

    /*设备列表*/
    public function device_list()
    {
        $device_name = I("get.device_name");                    //设备名称
        $device_id = I("get.device_no");                        //设备编号
        $user_name = I("get.user_name");                        //使用人员

        $dWhere = array();
        //设备名称
        if(!empty($device_name))
        {
            $dWhere['a.READER_NAME'] = array("LIKE", '%'.$device_name.'%');
        }
        //设备编号
        if(!empty($device_id))
        {
            $dWhere['a.READER_ID'] = array("LIKE", '%'.$device_id.'%');
        }
        //使用人员
        if(!empty($user_name))
        {
            $dWhere['b.USER_NAME'] = array("LIKE", '%'.$user_name.'%');
        }
        //列表
        $list = M("SERVICE_T_READER")->alias("a")
            ->field("a.READER_ID,a.READER_NAME,a.REG_TIME,b.USER_NAME,b.CELLPHONE")
            ->join("LEFT JOIN SERVICE_T_USER AS b ON a.USER_id=b.USER_ID")
            ->where($dWhere)
            ->select();

        //设备数
        $countList = M("SERVICE_T_READER")->count("READER_ID");

        $this->assign("list", $list);
        $this->assign("servercount", $countList);
        $this->assign("title", L("_DEVICE_LIST_"));
        $this->display("device_list");
    }

    /**
     * 导出
     */
    public function export_device_list()
    {
        //列表
        $list = M("SERVICE_T_READER")->alias("a")
            ->field("a.READER_ID,a.READER_NAME,a.REG_TIME,b.USER_NAME,b.CELLPHONE")
            ->join("LEFT JOIN SERVICE_T_USER AS b ON a.USER_id=b.USER_ID")
            ->select();

        $header = "序号,设备名称,设备编号,使用人员,联系方式,领用时间"."\n";

        $str = iconv('utf-8','gb2312', $header);
        foreach($list as $key=>$li)
        {
            $k = $key;                                                                     //序号
            $reader_name = iconv('utf-8','gb2312', $li['READER_NAME']);                //设备名称
            $reader_id = iconv('utf-8','gb2312', $li['READER_ID']);                    //设备编号
            $user_name = iconv('utf-8','gb2312', $li['USER_NAME']);                    //使用人员
            $cellphone = iconv('utf-8','gb2312', $li['CELLPHONE']);                    //联系方式
            $reg_time = iconv('utf-8','gb2312', $li['REG_TIME']);                    //领用时间

            $str .= $k . "," . $reader_name . "," . $reader_id . "," . $user_name . "," . $cellphone . "\t"  . "," . $reg_time . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_DEVICE_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }
}