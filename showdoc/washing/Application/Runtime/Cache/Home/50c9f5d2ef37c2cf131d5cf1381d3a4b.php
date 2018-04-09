<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
<meta name="author" content="ifuner，18658226071@163.com"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title><?php echo ($title); ?></title>
<link rel="shortcut icon" href="/Public/static/css/images/favicon.ico"/>
<!--每个页面都必须引用的css-->
<link rel="stylesheet" href="/Public/static/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="/Public/static/lib/layer/skin/default/layer.css" media="all">
    <meta http-equiv="Expires" CONTENT="0">
    <meta http-equiv="Cache-Control" CONTENT="no-cache">
    <meta http-equiv="Pragma" CONTENT="no-cache">
    <!--私有样式-->
    <link rel="stylesheet" href="/Public/static/css/login.css">
</head>
<body>

<div class="canvas_bgc">
    <canvas id="canvas">浏览器不支持canvas</canvas>

    <div class="user_login">
        <a href="javascript:" class="logo">
            <img src="/Public/static/images/e3logo.jpg" alt="" alt="logo">
            
        </a>
        <h1 class="tac">
            用户登录
        </h1>
        <div class="text">
            <span class="layui-icon">&#xe612;</span>
            <input type="text" name="" id="ac" placeholder="请输入手机号">
        </div>
        <div class="text"><span class="layui-icon">&#xe609;</span>
            <input type="password" name="" id="pw" placeholder="请输入密码">
        </div>

        <div class="validate fl">
            <input type="text" class="fl" id="v_code">
            <input type="hidden" name="session_id"/>
            <span class="fl identifying_code">
               <img src="<?php echo U('Login/getVerify');?>" alt="验证码">
            </span>
            <a href="javascript:;" class="dont_see fr">看不清</br>换一张?</a>
        </div>

        <div class="layui-form-item layui-form">
            <div class="layui-inline">
                <div class="fl fsize14">
                    选择语言/select language
                </div>
                <div class="layui-input-inline">
                    <select name="modules" id="choose-lang" lay-verify="required" lay-search="">
                        <option value="cn" selected>中文</option>
                        <option value="hk">繁体中文</option>
                        <option value="en">English</option>
                    </select>
                </div>
            </div>
        </div>
        <a href="javascript:" class="login_btn" href="javascript:;">
            登录
        </a>
    </div>
</div>
</body>

<!--每个页面都必须引入-->

<script src="/Public/static/lib/layui/layui.js"></script>
<script src="/Public/static/js/layui_config.js"></script>
<script src="/Public/static/lib/jquery-3.1.0/jquery-3.1.0.min.js"></script>
<!--按每个页面引入-->
<script src="/Public/static/js/login.js"></script>
<script src="/Public/static/lib/canvas.js"></script>
</html>