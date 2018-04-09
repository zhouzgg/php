<?php
/**
 * 分类管理
 */
namespace Home\Controller;
use Home\Model\ClassifyModel;
use Home\Model\CustomerModel;
use Think\Controller;

/*控制器的名称必须和文件夹的保持一致*/
class ClassifyController extends CommonController {

    /*新建分类*/
    public function add_classify()
    {
        //获取分类
        //$classifyModel = new ClassifyModel();
        //$list = $classifyModel->getAllClassify();
        //添加无上级分类
        $noParentList = array(array("LINEN_ID"=>0,"LINEN_NAME"=>"无上级分类","PARENT_ID"=>0));
        //$list = array_merge($noParentList, $list);
        $list = $noParentList;

        $customerModel = new CustomerModel();
        //客户列表
        $customerList = $customerModel->getAllCustomer();

        $this->assign("list", $list);
        $this->assign("customerList", $customerList);
        $this->assign("title", L("_CLASSIFY_ADD_"));
        $this->display("add_classify");
    }

    /**
     * 提交分类
     */
    public function post_classify()
    {
        $parent_id = I("post.parent_id");                   //上级分类
        $customer_id = I("post.customer_id");               //客户id
        $cname = I("post.cname");                             //分类名称

        if(empty($cname))
        {
            backResult(0, L("_CLASSIFY_NAME_IS_NULL_"));
        }
        if(!empty($parent_id) && is_numeric($parent_id))
        {
            $sData['PARENT_ID'] = $parent_id;                   //父类别ID
        } else {
            $sData['PARENT_ID'] = 0;
        }
        $sData['LINEN_NAME'] = $cname;                         //布草名称

        if(!empty($customer_id) && is_numeric($customer_id))
        {
            $sData['CUSTOMER_ID'] = $customer_id;
        }

        $ret = M("SERVICE_T_LINEN")->data($sData)->add();
        if($ret)
        {
            backResult(1, L("_ADD_SUCCESS_"));
        }
        else
        {
            backResult(0, L("_ADD_ERROR_"));
        }
    }

    /*分类管理*/
    public function classify()
    {
        $this->assign("title", L("_CLASSIFY_LIST_"));
        $this->display("classify");
    }

    /**
     * 分类列表
     */
    public function getClassifyList()
    {
        $classifyId = I("post.id");
        if(!is_numeric($classifyId))
        {
            $classifyId = "";
        }
        //获取分类
        $classifyModel = new ClassifyModel();
        $list = $classifyModel->getClassifyTree($classifyId);

        backResult(1, "success", $list);
    }

    /**
     * 根据客户id获取分类
     */
    public function getClassifyByCustomer()
    {
        $customer_id = I("get.customer_id");               //客户id

        if(!empty($customer_id) && is_numeric($customer_id))
        {
            //根据客户id获取分类
            $classifyModel = new ClassifyModel();

            $list = $classifyModel->getClassifyByCustomer($customer_id);

            $noParentList = array(array("LINEN_ID"=>0,"LINEN_NAME"=>"无上级分类","PARENT_ID"=>0));

            $list = array_merge($noParentList, $list);
        }
        backResult(1, "success", $list);
    }

    /**
     * 编辑分类
     */
    public function editClassify()
    {
        $line_id = I("post.line_id");               //分类id
        $line_name = I("post.line_name");           //分类名称
        $parent_id = I("post.parent_id");           //上级id

        if(!empty($line_id) && is_numeric($line_id))
        {
            if(!empty($line_name))
            {
                $saveData['LINEN_NAME'] = $line_name;
            }

            if(!empty($parent_id) && is_numeric($parent_id))
            {
                $saveData['PARENT_ID'] = $parent_id;
            }
            M("SERVICE_T_LINEN")->where(array("LINEN_ID"=>$line_id))->save($saveData);

            backResult(1, L("_OPERATING_SUCCESS_"));
        }
        backResult(0, L("_CLASSIFY_NOT_ERROR_"));
    }
}