<?php
namespace Home\Controller;
use Think\Controller;
use Home\Controller\TreeCommon;

class CommonController extends Controller {

    protected $CONTROLLERS = CONTROLLER_NAME;                           //当前控制器

    protected $ACTIONS = ACTION_NAME;                                  //当前函数

    /*初始化*/
    public function _initialize()
    {
        $user_id = session('USER_ID');
        //未登录
        if(empty($user_id))
        {
            header("Content-type: text/html; charset=utf-8");
            $this->redirect('Login/login', '', 3, L("_LOGIN_TIMEOUT_"));
        }
        else
        {
            $function_result = session('function_result');
            if(empty($function_result))
            {
                //获取登录者角色
                $roleInfo = M("SERVICE_T_USER_ROLE")->alias("a")
                    ->field("a.ROLE_ID,b.ROLE_MARK")
                    ->join("LEFT JOIN SERVICE_T_ROLE AS b ON a.ROLE_ID=b.ROLE_ID")
                    ->where(array("USER_ID"=>$user_id))
                    ->find();

                //获取权限
                if($roleInfo['ROLE_MARK'] == 'ADMIN')
                {
                    $functionData = $this->getAdminFunction();
                }
                else
                {
                    $functionData = $this->getOtherFunction($roleInfo['ROLE_ID']);
                }
                //树级
                $tree = new TreeCommon("id", "parent_id", "children");

                $tree->initTree($functionData['data']);

                $result = $tree->getTree();

                session('function_index', $functionData['index']);
                session('function_result', $result);
            }
            $this->assign("index", session('function_index'));
            $this->assign("result", session('function_result'));
        }
        $this->viewThisPage();
    }

    /**
     * 获取管理员权限
     */
    protected function getAdminFunction()
    {
        //权限数据
        $function = M("SERVICE_T_FUNCTION")
            ->field("FUNCTION_ID AS id,parent_id,FUNCTION_NAME,FUNCTION_URL AS url,FUNCTION_CONTROLLER AS controller,FUNCTION_ACTION AS action")
//            ->order("FUNCTION_RANG ASC")
            ->where(array("FUNCTION_DISPLAY"=>0))
            ->select();
        $indexData = array();
        //ps:因为前端页面左侧菜单特殊性，需另外处理
        foreach($function as $key=>$fun)
        {
            if(strtolower($fun['controller']) == "index" && strtolower($fun['action']) == "index")
            {
                $fun['name'] = L($fun['FUNCTION_NAME']);
                $indexData = $fun;
                unset($function[$key]);
            }
            $function[$key]['name'] = L($fun['FUNCTION_NAME']);
            unset($function[$key]['FUNCTION_NAME'] );
        }
        $functionData = array(
            'data' => $function,
            'index' => $indexData
        );
        return $functionData;
    }
    /**
     * 获取其他角色权限
     */
    protected function getOtherFunction($role_id)
    {
        //权限数据
        $function = M("SERVICE_T_FUNCTION")->alias("a")
            ->field("a.FUNCTION_ID AS id,a.parent_id,a.FUNCTION_NAME,a.FUNCTION_URL AS url,a.FUNCTION_CONTROLLER AS controller,a.FUNCTION_ACTION AS action")
            ->join("LEFT JOIN SERVICE_T_ROLE_FUNCTION AS b ON a.FUNCTION_ID=b.FUNCTION_ID")
//            ->order("FUNCTION_RANG ASC")
            ->where(array("b.ROLE_ID"=>$role_id,"a.FUNCTION_DISPLAY"=>0))
            ->select();
        $indexData = array();
        //ps:因为前端页面左侧菜单特殊性，需另外处理
        foreach($function as $key=>$fun)
        {
            if(strtolower($fun['controller']) == "index" && strtolower($fun['action']) == "index")
            {
                $fun['name'] = L($fun['FUNCTION_NAME']);
                $indexData = $fun;
                unset($function[$key]);
            }
            $function[$key]['name'] = L($fun['FUNCTION_NAME']);
            unset($function[$key]['FUNCTION_NAME'] );
        }
        $functionData = array(
            'data' => $function,
            'index' => $indexData
        );
        return $functionData;
    }
    /**
     * 当前页面
     */
    protected function viewThisPage()
    {
        $controller = $this->CONTROLLERS;                          //当前控制器
        $action = $this->getLastAction();//$this->ACTIONS;                                  //当前函数

        //首页
        if(strtolower($controller) == "index" && strtolower($action) == "index")
        {
            $this->assign("indexNode", 'layui-this');
        }
        //子节点
        $childNodeArray = M("SERVICE_T_FUNCTION")->field("FUNCTION_ID AS id,parent_id")->where(array("FUNCTION_CONTROLLER"=>$controller, "FUNCTION_ACTION"=>$action, "FUNCTION_DISPLAY"=>0))->find();
        if(!empty($childNodeArray))
        {
            $this->assign("childNode", $childNodeArray['id']);

            if(!empty($childNodeArray['parent_id']))
            {
                //第二级
                $lNodeArray = M("SERVICE_T_FUNCTION")->field("FUNCTION_ID AS id,parent_id,FUNCTION_CONTROLLER AS controller,FUNCTION_ACTION AS action")->where(array("FUNCTION_ID"=>$childNodeArray['parent_id'], "FUNCTION_DISPLAY"=>0))->find();

                if(!empty($lNodeArray['controller']) && !empty($lNodeArray['action']) || !empty($lNodeArray['parent_id']))
                {
                    $this->assign("lNode", $lNodeArray['id']);
                    //最顶级
                    $nodeArray = M("SERVICE_T_FUNCTION")->field("FUNCTION_ID AS id,parent_id")->where(array("FUNCTION_ID"=>$lNodeArray['parent_id'], "FUNCTION_DISPLAY"=>0))->find();
                    $this->assign("node", $nodeArray['id']);
                }
                else
                {
                    $this->assign("speNode", $childNodeArray['id']);
                    $this->assign("node", $lNodeArray['id']);
                }
            }
        }
    }

    /*解决左侧菜单问题*/
    public function getLastAction()
    {
        $controller = strtolower($this->CONTROLLERS);                          //当前控制器
        $action = strtolower($this->ACTIONS);                                  //当前函数
        switch($controller)
        {
            case 'washingplants':
                if($action == "washing_plant_list_details") {
                    $action = "washing_plant_list";
                }
                break;
            case 'custom':
                if($action == "custom_list_details") {
                    $action = "custom_list";
                }
                break;
            case 'storehouse':
                if($action == "storehouse_list_details") {
                    $action = "storehouse_list";
                }
                break;
            case 'inventory':
                if($action == "inventory" || $action == "inventory_details") {
                    $action = "choose";
                }
                break;
            case 'newproduct':
                if($action == "new_product_details") {
                    $action = "new_product";
                }
                break;
            case 'lossmanage':
                $verify = I("get.verify");
                if($action == "break_list_details" && empty($verify)) {
                    $action = "break_list";
                }
                if($action == "lose_list_details" && empty($verify)) {
                    $action = "lose_list";
                }
                if($action == "break_list_details" && !empty($verify)) {
                    $action = "break_verify";
                }
                if($action == "lose_list_details" && !empty($verify)) {
                    $action = "lose_verify";
                }
                break;
            case 'user':
                if($action == "user_add") {
                    $action = "user_choose";
                }
                if($action == "user_from_auth" || $action == "user_list_auth") {
                    $action = "user_list";
                }
                break;
            case 'order':
                if($action == "dirty_order" || $action == "dirty_order_details") {
                    $action = "choose_dirty";
                }
                if($action == "clean_order" || $action == "clean_order_details") {
                    $action = "choose_clean";
                }
                if($action == "detail_linen_month_order" || $action == "choose_linen") {
                    $action = "choose_linen";
                }
                if($action == "sender_linen_month_order" || $action == "sender_choose_linen") {
                    $action = "sender_choose_linen";
                }
                if($action == "recriver_linen_month_order" || $action == "recriver_choose_linen") {
                    $action = "recriver_choose_linen";
                }

                if($action == "day_detail_linen_month_order" || $action == "day_choose_linen") {
                    $action = "day_choose_linen";
                }
                if($action == "day_sender_linen_month_order" || $action == "day_sender_choose_linen") {
                    $action = "day_sender_choose_linen";
                }
                if($action == "day_recriver_linen_month_order" || $action == "day_recriver_choose_linen") {
                    $action = "day_recriver_choose_linen";
                }



                break;
        }
        return $action;
    }
}