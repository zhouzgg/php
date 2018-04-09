<?php
/**
 * 人员管理
 */

namespace Home\Controller;
use Home\Model\DepartmentModel;
use Home\Model\UserModel;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/

class UserController extends CommonController
{
    /*新建用户*/
    public function user_add()
    {
        $cid = I("get.cid");                    //用户类别
        $did = I("get.did");                    //身份

        if($cid == "admin")
        {
            //管理员角色列表
            $fWhere = array("ROLE_MARK"=>array("NOT IN", array(C('FINANCEROLE'),C('LINENROLE'))));
        }
        else
        {
            switch($did)
            {
                case 'fin':
                    //财务人员
                    $fWhere = array("ROLE_MARK"=>"FINANCEROLE");
                    //财务人员 必须关联客户
                    $customerList = $this->get_customer_list();
                    break;
                case 'dis':
                    //收发管理员
                    $fWhere = array("ROLE_MARK"=>"LINENROLE");
                    //收发管理员 必须关联到部门，部门为多选
                    $customerList = $this->get_customer_list();
                    break;
            }
        }
        //角色列表
        $roleList = M("SERVICE_T_ROLE")->field("ROLE_ID,ROLE_NAME")->where($fWhere)->select();

        $this->assign("customerList", $customerList);
        $this->assign("rolelist", $roleList);
        $this->assign("cids", $cid);
        $this->assign("dids", $did);
        $this->assign("title", L("_USER_ADD_"));
        $this->display("user_add");
    }

    /*获取客户列表*/
    public function get_customer_list()
    {
        $list = M("SERVICE_T_CUSTOMER")->alias("a")
            ->field("a.CUSTOMER_ID AS WAREHOUSE_ID,a.CUSTOMER_NAME AS WAREHOUSE_NAME")
            ->select();
        return $list;
    }

    /*获取部门列表*/
    public function get_department_list()
    {
        $customer_id = I("post.customer_id");                   //客户id

        if(!empty($customer_id))
        {
            $list = M("SERVICE_T_DEPARTMENT")->alias("a")
                ->field("a.DEPARTMENT_ID,a.DEPARTMENT_NAME")
                ->join("left join SERVICE_T_CUSTOMER as b on b.CUSTOMER_ID=a.CUSTOMER_ID")
                ->where(array('a.CUSTOMER_ID'=>$customer_id))
                ->group("DEPARTMENT_ID")
                ->select();
        }

        backResult(1, "success", $list);
    }

    /**
     * 部门2
     */
    public function getdepartmentlist()
    {
        $customer_id = I("param.customer_id");                   //客户id
        //调用部门数据
        $departMentModel = new DepartmentModel();
        $result = $departMentModel->getDepartmentSecond($customer_id);

        if(!empty($result))
        {
            //无上级部门
            $notResult = array(array("value" => 0,"parent_id" => 0,"title" => "全选","checked" => false,"disabled"=>false,"data"=>[]));
            $resultnew = array_merge($notResult, $result);
        }
        else
        {
            $resultnew = array();//$notResult;
        }
        echo json_encode($resultnew, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /*用户提交*/
    public function post_user()
    {
        $user_id = I("post.user_id");                           //用户id
        $user_name = I("post.user_name");                       //用户名
        $password = I("post.password");                         //密码
        $true_name = I("post.true_name");                       //真实名称
        $collphone = I("post.collphone");                       //联系电话
        $role_id = I("post.role_id");                           //角色id
        $customer_id = I("post.customer_id");                   //客户id
        $department_id = I("post.department_id");               //部门id
        $departmentArray = explode(",", $department_id);          //部门数组

        if(empty($user_name))
        {
            backResult(0, L("_USER_NOT_USERNAME_"));
        }
        if(empty($true_name))
        {
            backResult(0, L("_USER_NOT_TRUENAME_"));
        }
        if(empty($collphone))
        {
            backResult(0, L("_USER_NOT_COLLPHONE_"));
        }
        if(empty($role_id))
        {
            backResult(0, L("_USER_NOT_ROLE_ID_"));
        }
        //角色信息
        $roleInfo = M("SERVICE_T_ROLE")->field("ROLE_MARK")->where(array("ROLE_ID"=>$role_id))->find();

        //收发管理员、财务人员必须关联客户
        if((strtolower($roleInfo['ROLE_MARK']) == strtolower(C("LINENROLE")) || strtolower($roleInfo['ROLE_MARK']) == strtolower(C("FINANCEROLE"))) && empty($customer_id))
        {
            backResult(0, L("_USER_NOT_SELECT_CUSTOMER_"));
        }
        //收发管理员 必须关联到部门，部门为多选
        if(strtolower($roleInfo['ROLE_MARK']) == strtolower(C("LINENROLE")) && empty($departmentArray[0]))
        {
            backResult(0, L("_USER_NOT_SELECT_DEPARTMENT_"));
        }
        $userModel = new UserModel();
        //新增
        if(empty($user_id))
        {
            if(empty($password))
            {
                backResult(0, L("_USER_NOT_PASSWORD_"));
            }
            //用户名检验
            if($userModel->checkUser(array("USER_LOGINAME"=>$user_name)))
            {
                backResult(0, L("_USER_USERNAME_EXIT_"));
            }
            //联系电话检验
            if($userModel->checkUser(array("CELLPHONE"=>$collphone)))
            {
                backResult(0, L("_USER_COLLPHONE_EXIT_"));
            }
            $addData = array(
                'USER_LOGINAME' => $user_name,
                'USER_NAME' => $true_name,
                'USER_PASSWD' => $password,
                'CELLPHONE' => $collphone,
                'REG_TIME' => date("Y-m-d H:i:s")
            );
            //添加用户主数据
            $ret = M("SERVICE_T_USER")->data($addData)->add();
            if($ret)
            {
                //角色信息
                //$roleInfo = M("SERVICE_T_ROLE")->field("ROLE_MARK")->where(array("ROLE_ID"=>$role_id))->find();
                //添加用户属性表
                $proData = array(
                    'USER_ID' => $ret,
                    'PROP_NAME' => $roleInfo['ROLE_MARK'],
                    'PROP_VALUE' => $role_id,
                );
                M("SERVICE_T_USER_PROPERTY")->data($proData)->add();
                //添加角色用户关系表
                $roleData = array(
                    'ROLE_ID' => $role_id,
                    'USER_ID' => $ret
                );
                M("SERVICE_T_USER_ROLE")->data($roleData)->add();

                //财务人员必须关联客户
                if(!empty($customer_id) && (strtolower($roleInfo['ROLE_MARK']) == strtolower(C("FINANCEROLE")) || strtolower($roleInfo['ROLE_MARK']) == strtolower(C("LINENROLE"))))
                {
                    $customerData = array(
                        'USER_ID' => $ret,
                        'CUSTOMER_ID' => $customer_id,
                    );
                    //用户与客户关联表
                    M("SERVICE_T_USER_CUSTOMER")->data($customerData)->add();
                }

                //收发管理员 必须关联到部门，部门为多选
                if(strtolower($roleInfo['ROLE_MARK']) == strtolower(C("LINENROLE")))
                {
                    //可能选择了多个部门
                    foreach($departmentArray as $department)
                    {
                        $departmentData = array(
                            'USER_ID' => $ret,
                            'DEPARTMENT_ID' => $department,
                        );
                        //用户与部门关联表
                        M("SERVICE_T_USER_DEPRTMENT")->data($departmentData)->add();
                    }
                }
                backResult(1, L("_USER_ADD_SUCCESS_"));
            }
            else
            {
                backResult(0, L("_USER_ADD_ERROR_"));
            }
        }
        else
        {
            //编辑
            //用户名检验
            if($userModel->checkUser(array("USER_LOGINAME"=>$user_name,"USER_ID"=>array("NEQ", $user_id))))
            {
                backResult(0, L("_USER_USERNAME_EXIT_"));
            }
            //联系电话检验
            if($userModel->checkUser(array("CELLPHONE"=>$collphone,"USER_ID"=>array("NEQ", $user_id))))
            {
                backResult(0, L("_USER_COLLPHONE_EXIT_"));
            }
            $saveData = array(
                'USER_LOGINAME' => $user_name,
                'USER_NAME' => $true_name,
                //'USER_PASSWD' => $password,
                'CELLPHONE' => $collphone
            );
            //编辑用户表
            M("SERVICE_T_USER")->where(array("USER_ID"=>$user_id))->save($saveData);

            /*//编辑用户属性表
            $proData = array(
                'PROP_NAME' => $roleInfo['ROLE_MARK'],
                'PROP_VALUE' => $role_id,
            );
            M("SERVICE_T_USER_PROPERTY")->where(array("USER_ID"=>$user_id))->save($proData);
            //重新添加角色用户表
            M("SERVICE_T_USER_ROLE")->where(array("USER_ID"=>$user_id))->save(array('ROLE_ID' => $role_id));

            //财务人员必须关联客户
            if(!empty($customer_id) && (strtolower($roleInfo['ROLE_MARK']) == strtolower(C("FINANCEROLE")) || strtolower($roleInfo['ROLE_MARK']) == strtolower(C("LINENROLE"))))
            {
                //用户与客户关联表
                M("SERVICE_T_USER_CUSTOMER")->where(array('USER_ID' => $user_id))->save(array('CUSTOMER_ID' => $customer_id));
            }

            //收发管理员 必须关联到部门，部门为多选
            if(strtolower($roleInfo['ROLE_MARK']) == strtolower(C("LINENROLE")))
            {
                //先移除之前的
                M("service_t_user_department")->where(array('USER_ID' => $user_id))->delete();
                //可能选择了多个部门
                foreach($departmentArray as $department)
                {
                    $departmentData = array(
                        'USER_ID' => $user_id,
                        'DEPARTMENT_ID' => $department,
                    );
                    //用户与部门关联表
                    M("service_t_user_department")->where($departmentData)->add();
                }
            }*/

            backResult(1, L("_USER_EDIT_SUCCESS_"));
        }
    }

    /*编辑密码（ps：因为前端页面密码与其他信息分开的原因，所以单独出来）*/
    public function updatePassword()
    {
        $user_id = I("post.user_id");                           //用户id
        $password = I("post.password");                         //密码
        if(!empty($user_id))
        {
            M("SERVICE_T_USER")->where(array("USER_ID"=>$user_id))->save(array("USER_PASSWD"=>$password));
        }
        backResult(1, L("_USER_PASSWORD_EDIT_SUCCESS_"));
    }

    /*角色提交（新建或编辑）*/
    public function post_role()
    {
        $role_id = I("post.role_id");                       //角色Id
        $role_name = I("post.role_name");                   //角色名称

        if (empty($role_name)) {
            backResult(0, L("_USER_ROLE_NOT_NAME_"));
        }
        if (empty($role_id)) {
            //确认是否存在
            $roleInfo = M("SERVICE_T_ROLE")->field("ROLE_ID")->where(array("ROLE_NAME" => $role_name))->find();
            if(!empty($roleInfo))
            {
                backResult(0, L("_USER_ROLE_NAMEXIT_"));
            }
            //新增
            $ret = M("SERVICE_T_ROLE")->data(array("ROLE_NAME" => $role_name))->add();
            if ($ret) {
                backResult(1, L("_ADD_SUCCESS_"));
            } else {
                backResult(0, L("_ADD_ERROR_"));
            }
        } else {
            //确认是否存在
            $roleInfo = M("SERVICE_T_ROLE")->field("ROLE_ID")->where(array("ROLE_NAME" => $role_name, "ROLE_ID" => array("NEQ", $role_id)))->find();

            if(!empty($roleInfo))
            {
                backResult(0, L("_USER_ROLE_NAMEXIT_"));
            }
            //编辑
            M("SERVICE_T_ROLE")->where(array("ROLE_ID" => $role_id))->save(array("ROLE_NAME" => $role_name));

            backResult(1, L("_EDIT_SUCCESS_"));
        }
    }

    /*删除角色*/
//    public function del_user_role()
//    {
//        $role_id = I("post.role_id");                       //角色id
//        if (empty($role_id)) {
//            backResult(0, L("_DATA_ERROR_"));
//        }
//        $ret = M("SERVICE_T_USER_ROLE")->where(array("ROLE_ID" => $role_id))->delete();
//        if ($ret) {
//            backResult(1, "success");
//        } else {
//            backResult(0, L("_DATA_ERROR_"));
//        }
//    }

    /*权限管理*/
    public function user_auth()
    {
        //角色列表
        $roleList = M("SERVICE_T_ROLE")->field("ROLE_ID,ROLE_NAME")->select();//->where("`ROLE_MARK`='' || `ROLE_MARK` IS NULL")

        $this->assign("rolelist", $roleList);
        $this->assign("title", L("_USER_AUTH_MANAGE_"));
        $this->display("user_auth");
    }

    /*用户跳转权限管理*/
    public function user_from_auth()
    {
        $role_id = I("param.rid");                  //角色id
        $user_id = I("param.aid");                  //用户id
        //角色列表
        $roleList = M("SERVICE_T_ROLE")->field("ROLE_ID,ROLE_NAME")->where("`ROLE_MARK`='' || `ROLE_MARK` IS NULL")->select();

        $this->assign("user_id", $user_id);
        $this->assign("role_id", $role_id);
        $this->assign("rolelist", $roleList);
        $this->assign("title", L("_USER_AUTH_MANAGE_"));
        $this->display("user_auth");
    }

    /**
     * 权限提交
     */
    public function post_auth()
    {
        $role_id = I("post.role_id");                           //角色Id
        $role_function = I("post.role_function");              //权限

        if(empty($role_id))
        {
            backResult(0, L("_USER_NOT_ROLE_ID_"));
        }
        $role_function_Array = explode(",", $role_function);
        if(empty($role_function_Array))
        {
            backResult(0, L("_USER_NOT_SELECT_AUTH_"));
        }
        //先删除原先的
        M("SERVICE_T_ROLE_FUNCTION")->where(array('ROLE_ID'=>$role_id))->delete();
        $sData = [];
        foreach($role_function_Array as $fun)
        {
            $fData['ROLE_ID'] = $role_id;
            $fData['FUNCTION_ID'] = $fun;

            $sData[] = $fData;
        }
        //新增
        M("SERVICE_T_ROLE_FUNCTION")->addAll($sData);

        backResult(1, L("_USER_ADD_AUTH_SUCCESS_"));
    }

    /**
     * 获取角色权限
     */
    public function get_role_auth()
    {
        $role_id = I("post.role_id");                           //角色id

        $selectAuth = array();
        if(!empty($role_id))
        {
            //角色权限（已经选择的）
            $roleAuth = M("SERVICE_T_ROLE_FUNCTION")->field("ROLE_ID,FUNCTION_ID")->where(array("ROLE_ID"=>$role_id))->select();
            if(!empty($roleAuth))
            {
                foreach($roleAuth as $role)
                {
                    $selectAuth[$role['FUNCTION_ID']] = $role['ROLE_ID'];
                }
            }
        }
        //$userModel = new UserModel();
        //$result = $userModel->getFunctionTree($selectAuth);
        $functionData = M("SERVICE_T_FUNCTION")->field("FUNCTION_ID AS id,PARENT_ID AS pId,FUNCTION_NAME AS name")->select();

        foreach($functionData as $key=>$fun)
        {
            if(isset($selectAuth[$fun['id']]))
            {
                $functionData[$key]['checked'] = true;
            }
            $functionData[$key]['name'] = L($fun['name']);
            $functionData[$key]['open'] = true;
        }
        backResult(1, "success", $functionData);
    }

    /*角色管理*/
    public function user_part()
    {
        //角色列表
        $roleList = M("SERVICE_T_ROLE")->field("ROLE_ID,ROLE_NAME,ROLE_MARK")->select();

        $this->assign("rolelist", $roleList);
        $this->assign("title", L("_USER_ROLE_MANAGE_"));
        $this->display("user_part");
    }

    /*用户管理列表*/
    public function user_list()
    {
        $name = I("get.user_name");                  //姓名
        $role_id = I("get.role_id");               //身份
        //用户名称
        //$userNameInfo = $this->get_user_list($user_id);
        $sWhere = array();
        if(!empty($name))
        {
            $sWhere['a.USER_NAME'] = array("LIKE", "%".$name."%");
        }
        if(!empty($role_id))
        {
            $sWhere['b.ROLE_ID'] = array("LIKE", "%".$name."%");
        }
        $userInfo = M("SERVICE_T_USER")->alias("a")
            ->field("a.USER_ID,a.USER_NAME,a.USER_LOGINAME,a.REG_TIME,a.CELLPHONE,c.ROLE_ID,c.ROLE_NAME,c.ROLE_MARK")
            ->join("LEFT JOIN SERVICE_T_USER_ROLE AS b ON b.USER_ID=a.USER_ID")
            ->join("LEFT JOIN SERVICE_T_ROLE AS c ON c.ROLE_ID=b.ROLE_ID")
            ->where($sWhere)
            ->select();

        foreach($userInfo as $key=>$user)
        {
            switch($user['ROLE_MARK'])
            {
                case C("FINANCEROLE"):
                case C("LINENROLE"):
                    $userInfo[$key]['AUTH'] = 1;
                    break;
                default:
                    $userInfo[$key]['AUTH'] = 0;
                    break;
            }
        }

        $this->assign("userInfo", $userInfo);
        $this->assign("rolename_list", $roleNameInfo);
        $this->assign("title", L("_USER_ROLE_MANAGE_LIST_"));
        $this->display("user_list");
    }

    /*导出*/
    public function export_user_list()
    {
        $name = I("get.name");                  //姓名
        $role_id = I("get.role_id");               //身份
        $sWhere = array();
        if(!empty($name))
        {
            $sWhere['a.USER_NAME'] = array("LIKE", "%".$name."%");
        }
        if(!empty($role_id))
        {
            $sWhere['b.ROLE_ID'] = array("LIKE", "%".$name."%");
        }
        $userInfo = M("SERVICE_T_USER")->alias("a")
            ->field("a.USER_ID,a.USER_NAME,a.USER_LOGINAME,a.REG_TIME,a.CELLPHONE,c.ROLE_NAME")
            ->join("LEFT JOIN SERVICE_T_USER_ROLE AS b ON b.USER_ID=a.USER_ID")
            ->join("LEFT JOIN SERVICE_T_ROLE AS c ON c.ROLE_ID=b.ROLE_ID")
            ->where($sWhere)
            ->select();

        //$header = "序号,用户名,姓名,身份,联系方式"."\n";
        $header = L("_USER_NO_").",".L("_USER_NAME_").",".L("_USER_FULL_NAME_").",".L("_USER_IDENTIFY_").",".L("_USER_PHONE_")."\t"."\n";

        $str = iconv('utf-8','GBK', $header);
        foreach($userInfo as $key=>$li)
        {
            $k = $li['USER_ID'];                                                         //序号
            $login_name = iconv('utf-8','GBK', $li['USER_LOGINAME']);                //用户名
            $user_name = iconv('utf-8','GBK', $li['USER_NAME']);                    //姓名
            $role_name = iconv('utf-8','GBK', $li['ROLE_NAME']);                    //身份
            $cellphone = iconv('utf-8','GBK', $li['CELLPHONE']);                    //联系方式

            $str .= $k . "," . $login_name . "," . $user_name . "," . $role_name . "," . $cellphone ."\t" . "\n";
        }
        $filename = iconv('UTF-8','GBK', L("_USER_ROLE_MANAGE_LIST_").'-'.time().'.csv');

        explode_csv($filename, $str);
    }

    /*用户单条数据*/
    public function single_user()
    {
        $user_id = I("param.id");                         //用户id
        if(!empty($user_id) && is_numeric($user_id))
        {
            $userInfo = M("SERVICE_T_USER")->alias("a")
                ->field("a.USER_ID,a.USER_NAME,a.USER_LOGINAME,a.REG_TIME,a.CELLPHONE")
                ->where(array("USER_ID"=>$user_id))
                ->find();
        }
        backResult(1, "success", $userInfo);
    }

    /*用户名称列表*/
    public function get_user_list($user_id)
    {
        $sWhere = array();
        if(!empty($user_id))
        {
            $sWhere['USER_ID'] = $user_id;
        }
        $userNameInfo = M("service_t_user")->field("USER_ID,USER_NAME")->where($sWhere)->select();

        return $userNameInfo;
    }

    /*角色名称列表*/
    public function get_role_list($role_id)
    {
        $sWhere = array();
        if(!empty($role_id))
        {
            $sWhere['ROLE_ID'] = $role_id;
        }
        $roleInfo = M("SERVICE_T_ROLE")->field("ROLE_ID,ROLE_NAME")->where($sWhere)->select();

        return $roleInfo;
    }

    /*选择分类  */
    public function user_choose()
    {
        $this->assign("title", L("_USER_ROLE_SELECT_TYPE_"));
        $this->display("user_choose");
    }

    /*用户权限设置*/
     public function user_list_auth()
    {
        //查找用户
        $user_id = I("get.aid");                        //用户id
        $roleArray = M("SERVICE_T_USER_ROLE")->alias("a")
            ->field("b.ROLE_ID,b.ROLE_MARK")->join("LEFT JOIN SERVICE_T_ROLE AS b ON b.ROLE_ID=a.ROLE_ID")->where(array("a.USER_ID"=>$user_id))->find();

        $this->assign("title", L("_USER_AUTH_SETTING_"));

        //财务人员与收发管理员角色情况
        if($roleArray['ROLE_MARK'] == C("FINANCEROLE") || $roleArray['ROLE_MARK'] == C("LINENROLE"))
        {
            //默认设置的权限
            $authArray = C("DISPATCHERAUTH");
            $functionModel = M("SERVICE_T_FUNCTION");
            $lastAuthArray = array();
            foreach($authArray as $key=>$auth)
            {
                $funData = $functionModel->field("FUNCTION_ID,FUNCTION_NAME")->where(array("FUNCTION_CONTROLLER"=>$auth['controller'],"FUNCTION_ACTION"=>$auth['action']))->find();
                $funData['FUNCTION_NAME'] = L($funData['FUNCTION_NAME']);
                $lastAuthArray[$key] = $funData;
            }
            $this->assign("authList", $lastAuthArray);

            $this->display("user_list_auth");
        }
        else
        {
            //$this->redirect('User/user_from_auth?aid='.$user_id);//array("rid"=>$roleArray['ROLE_ID'], "aid"=>$user_id)
            //角色列表
            $roleList = M("SERVICE_T_ROLE")->field("ROLE_ID,ROLE_NAME")->where("`ROLE_MARK`='' || `ROLE_MARK` IS NULL")->select();

            $this->assign("rolelist", $roleList);
            $this->assign("title", L("_USER_AUTH_MANAGE_"));
            $this->display("user_auth");
        }
    }

	/*获取用户id、name和phone*/
    public function getUserName()
    {
        $name = I("post.username");                 //用户名
        $uData = array();
        if(!empty($name))
        {
            $uData['USER_NAME'] = array("LIKE", "%".$name."%");
        }
        $list = M("SERVICE_T_USER")->field("USER_ID,USER_NAME,CELLPHONE")->where($uData)->select();

        backResult(1, "success", $list);
    }
}