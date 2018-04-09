<?php
namespace Home\Controller;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class ExcelController extends CommonController {

    /*报表管理*/
    public function excel()
    {
        $this->assign("title","报表管理");
        $this->display("excel");
    }


}