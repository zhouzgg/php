<?php
/**
 * 司机管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class DriverController extends CommonController {

    /**
     * 新增司机
     */
    public function add_driver()
    {
        $this->assign("title", L("_DRIVER_ADD_"));
        $this->display("add_driver");
    }

    /**
     * 添加/修改司机信息
     */
    public function post_driver()
    {
        $driver_id = I("post.driver_id");                     //司机id
        $driver_name = I("post.driver_name");                 //司机名称
        $driver_phone = I("post.drive_phone");                //联系方式

        //司机名称
        if(empty($driver_name))
        {
            backResult(0, L("_DRIVER_ADD_NOT_NAME_"));
        }
        //联系方式
        if(empty($driver_phone))
        {
            backResult(0, L("_DRIVER_ADD_NOT_PHONE_"));
        }
        if(empty($driver_id))
        {
            //添加
            $addData = array(
                'DRIVER_NAME' => $driver_name,
                'DRIVER_PHONE' => $driver_phone,
                'CREATE_TIME' => date("Y-m-d H:i:s")
            );
            $ret = M("SERVICE_T_DRIVER")->data($addData)->add();
            if($ret)
            {
                backResult(1, "success");
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
                'DRIVER_NAME' => $driver_name,
                'DRIVER_PHONE' => $driver_phone
            );
            $ret = M("SERVICE_T_DRIVER")->where(array("DRIVER_ID"=>$driver_id))->save($editData);
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

    /**
     * 司机列表
     */
    public function driver_list()
    {
        $list = M("SERVICE_T_DRIVER")->field("DRIVER_ID,DRIVER_NAME,DRIVER_PHONE,CREATE_TIME")->select();

        $this->assign("list", $list);
        $this->assign("title", L("_DRIVER_LIST_"));
        $this->display("driver_list");
    }

    /**
     * 导出
     */
    public function export_driver_list()
    {
        //列表
        $list = M("SERVICE_T_CAR")->field("DRIVER_ID,DRIVER_NAME,DRIVER_PHONE,CREATE_TIME")->select();

        //$header = "序号,司机姓名,手机号码"."\n";
        $header = L("_DRIVER_NO_").",".L("_DRIVER_NAME_").",".L("_DRIVER_PHONE_")."\n";
        $str = iconv('utf-8','GBK', $header);
        foreach($list as $li)
        {
            $driver_id = $li['DRIVER_ID'];                                                     //序号
            $driver_name = iconv('utf-8','GBK', $li['DRIVER_NAME']);                      //司机姓名
            $driver_phone = iconv('utf-8','GBK', $li['DRIVER_PHONE']);                    //手机号码

            $str .= $driver_id . "," . $driver_name . "," . $driver_phone . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_DRIVER_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }
}