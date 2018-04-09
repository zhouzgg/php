/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , common = layui.common;


     common.header();

    var moduleData = {}
        , username = $("#username")
        , phone = $("#phone");

    var ifuner = new allFunc();

    /*新增*/
    $(".add-btn").click(function () {
        ifuner.add();
    })

    /*清空表单*/
    $(".reset-btn").click(function () {
        ifuner.reset();
    });

    function allFunc() {
        // 实例方法
        this.add = function () {

            if (username.val() !== "") {
                /*手机号*/
                if (phone.val().length == 11) {

                    /*数据赋值收集*/
                    moduleData = {
                        driver_name: username.val(),
                        drive_phone: phone.val(),
                    }

                    $.ajax({
                        url: base.baseUrl + "index.php/Home/Driver/post_driver",
                        type: "post",
                        dataType: "json",
                        data: moduleData,
                        success: function (res) {
                            console.log(res);
                            if (res.result === 1) {
                                /*重置*/
                                ifuner.reset();
                            }
                            layer.alert(res.mesg, {
                                icon: 1,
                                skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                            })
                        }
                    })
                } else {
                    layer.tips('手机号格式不正确', '#phone', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('用户格式不正确', '#username', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }

        },
            this.reset = function () {
                $(".layui-form input").val("");
            }
    }


});
