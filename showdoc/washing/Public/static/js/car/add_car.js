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
        , car_name = $("#car_name")
        , car_id = $("#car_id");

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

            if (car_name.val() !== "") {
                /*手机号*/
                if (car_id.val() !== "") {

                    /*数据赋值收集*/
                    moduleData = {
                        car_name: car_name.val(),
                        car_phone: car_id.val(),
                    }

                    console.log(moduleData);

                    $.ajax({
                        url: base.baseUrl + "index.php/Home/Car/post_car",
                        type: "post",
                        dataType: "json",
                        data: moduleData,
                        success: function (res) {
                            console.log(res);
                            if (res.result === 1) {
                                /*重置*/
                                ifuner.reset();
                            }
                            var status = res.result;
                            status=(status==1?1:2);
                            layer.alert(res.mesg, {
                                icon: status,
                                skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                            })
                        }
                    })
                } else {
                    layer.tips('车牌号码不能为空', '#car_id', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('车辆名称不能为空', '#car_name', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }

        },
            this.reset = function () {
                $(".layui-form input").val("");
            }
    }


});
