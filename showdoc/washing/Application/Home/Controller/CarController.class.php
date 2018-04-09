<?php
/**
 * 车辆管理
 */
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class CarController extends CommonController {

    /**
     * 新增车辆
     */
    public function add_car()
    {
        $this->assign("title", L("_CAR_ADD_"));
        $this->display("add_car");
    }

    /**
     * 添加/修改车辆信息
     */
    public function post_car()
    {
        $car_id = I("post.car_id");                     //车辆id
        $car_name = I("post.car_name");                 //车辆名称
        $car_phone = I("post.car_phone");               //车辆号码

        //司机名称
        if(empty($car_name))
        {
            backResult(0, L("_CAR_ADD_NOT_NAME_"));
        }
        //联系方式
        if(empty($car_phone))
        {
            backResult(0, L("_CAR_ADD_NOT_PHONE_"));
        }
        if(empty($car_id))
        {
            //添加
            $addData = array(
                'CAR_NAME' => $car_name,
                'CAR_NUMBER' => $car_phone,
                'CREATE_TIME' => date("Y-m-d H:i:s")
            );
            $ret = M("SERVICE_T_CAR")->data($addData)->add();
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
                'CAR_NAME' => $car_name,
                'CAR_NUMBER' => $car_phone
            );
            $ret = M("SERVICE_T_CAR")->where(array("CAR_ID"=>$car_id))->save($editData);
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
     * 车辆列表
     */
    public function car_list()
    {
        $list = M("SERVICE_T_CAR")->field("CAR_ID,CAR_NAME,CAR_NUMBER,CREATE_TIME")->select();

        $this->assign("list", $list);
        $this->assign("title", L("_CAR_LIST_"));
        $this->display("car_list");
    }

    /**
     * 导出
     */
    public function export_car_list()
    {
        //列表
        $list = M("SERVICE_T_CAR")->field("CAR_ID,CAR_NAME,CAR_NUMBER,CREATE_TIME")->select();

        //$header = "序号,车辆名称,车牌号"."\n";
        $header = L("_CAR_NO_").",".L("_CAR_NAME_").",".L("_CAR_PHONE_")."\n";
        $str = iconv('utf-8','GBK', $header);
        foreach($list as $li)
        {
            $car_id = $li['CAR_ID'];                                                        //序号
            $car_name = iconv('utf-8','GBK', $li['CAR_NAME']);                         //车辆名称
            $car_number = iconv('utf-8','GBK', $li['CAR_NUMBER']);                    //车牌号

            $str .= $car_id . "," . $car_name . "," . $car_number . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_CAR_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }
}