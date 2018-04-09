/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , form = layui.form
        , element = layui.element
        , url = base.baseUrl;

    /*禁止返回*/
    $(function () {
        if (window.history && window.history.pushState) {
            $(window).on('popstate', function () {
                window.history.pushState('forward', null, '#');
                window.history.forward(1);
            });
        }

        window.history.pushState('forward', null, '#'); //在IE中必须得有这两行
        window.history.forward(1);
    })


    /*回车键登录*/
    document.onkeydown = function () {
        if (event.keyCode == 13) {
            $(".login_btn").click();
        }
    }

    /*登录正则验证*/
    $(".login_btn").on("click", function () {
        var ac_reg = /^0?(13[0-9]|15[012356789]|17[013678]|18[0-9]|14[57])[0-9]{8}$/;
        var pw_reg = /^[a-zA-Z0-9]\w{5,16}$/;
        var ac = $("#ac")
            , pw = $("#pw")
            , v_code = $("#v_code")
            , chooseLang = $("#choose-lang");

        var moduleData = {
            mobile: "",
            password: "",
            code: "",
            lang: "",
        }
        if (ac.val() !== "") {
            if (pw.val() !== "") {
                if (v_code.val().length == 4) {

                    /*登录操作*/
                    moduleData = {
                        mobile: ac.val(),
                        password: pw.val(),
                        code: v_code.val(),
                        lang: chooseLang.find("option:selected").val(),
                    }

                    $.ajax({
                        url: url + "index.php/Home/Login/post_login",
                        type: "post",
                        dataType: "json",
                        data: moduleData,
                        success: function (res) {
                            console.log(res);
                            if (res.result === 1) {
                                layer.msg('登录中，请稍等', {
                                    icon: 16
                                    , shade: 0.01
                                });
                                setTimeout(function () {
                                    window.location.replace("/index.php/Home/Index/index")
                                }, 1500);
                            } else {

                                var layAlert = layer.alert(res.mesg, {
                                    icon: 2,
                                    skin: 'layer-ext-moon',

                                }, function () {
                                    getImgCode();
                                    layer.close(layAlert);
                                })
                            }


                        }
                    })

                } else {
                    layer.tips('验证码长度不正确', '#v_code', {
                        tips: [2, '#FF0000'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('密码不能为空', '#pw', {
                    tips: [2, '#FF0000'] //还可配置颜色
                });
            }
        } else {
            layer.tips('账号不能为空', '#ac', {
                tips: [2, '#FF0000'] //还可配置颜色
            });
        }
    })


    /*验证码刷新*/
    $(".dont_see,.identifying_code img").click(function () {
        getImgCode();
    })

    function getImgCode() {
        var imgCode = $('.identifying_code img')
            , time = new Date().getTime()
            , src = imgCode.prop("src").split("?");
        src = src[0];
        imgCode.prop("src", src + "?time=" + time);
    }
});
