<?php
/**
 * 登录
 */
namespace Home\Controller;
use Think\Controller;

class LoginController extends Controller {

    /*登录*/
    public function login()
    {
        $this->assign("title", L("_LOGIN_PAGE_"));
        $this->display("login");
    }

    /*提交*/
    public function post_login()
    {
        $username = I("param.mobile");               //帐号
        $password = I("param.password");             //密码
        $code = I("param.code");                     //验证码
        $lang = I("param.lang");                      //语言

        if(empty($username))
        {
            backResult(0, L("_LOGIN_NOT_INPUT_USERNAME_"));
        }
        if(empty($password))
        {
            backResult(0, L("_LOGIN_NOT_INPUT_PASSWORD_"));
        }
        if(empty($code))
        {
            backResult(0, L("_LOGIN_NOT_INPUT_CODE_"));
        }
        if(!$this->check_verify($code))
        {
            backResult(0, L("_LOGIN_CODE_ERROR_"));
        }
        //用户信息
        $userInfo = M("SERVICE_T_USER")->field("USER_ID,USER_NAME,USER_PASSWD")->where(array("CELLPHONE"=>$username))->find();

        //密码校验
        //if(crypt($password, C("CRYPT")) != $userInfo['USER_PASSWD'])
        if($password != $userInfo['USER_PASSWD'])
        {
            backResult(0, L("_LOGIN_ERROR_"));
        }
        $language = $this->getLanguage($lang);
        session('USER_ID', $userInfo['USER_ID']);               //登录用户id
        session('USER_NAME', $userInfo['USER_NAME']);           //登录用户名
        //session('LANGS', $language);             //语言
        //C('DEFAULT_LANG', $this->getLanguage($lang));
        cookie('set_language', $language, 3600 * 12);
        backResult(1, "success");
    }

    /*登出*/
    public function logOut()
    {
        //session('USER_ID', null);               //登录用户id
        //session('USER_NAME', null);           //登录用户名
        session(null);                          //清除全部
        backResult(1, "success");
    }

    /*语言*/
    public function getLanguage($lang)
    {
        switch($lang)
        {
            case 'hk':
                $language = "zh-hk";
                break;
            case 'en':
                $language = "en-us";
                break;
            default:
                $language = "zh-cn";
                break;
        }
        return $language;
    }

    /*验证码*/
    public function getVerify()
    {
        $config =    array(
            'fontSize'    =>    32,    // 验证码字体大小
            'length'      =>    4,     // 验证码位数
            'useNoise'    =>    false, // 关闭验证码杂点
            'useCurve'  =>  false,// 关闭验证码线
        );
        $Verify =     new \Think\Verify($config);
        $Verify->entry();
    }

    /*验证*/
    function check_verify($code, $id = '')
    {
        $verify = new \Think\Verify();
        return $verify->check($code, $id);
    }
}