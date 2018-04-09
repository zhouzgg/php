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

    var moduleData = {};
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
            var  device_id =$("#device_id").val()
            ,device_name =$("#device_name").val()
            ,user_id =$("#receiver option:selected").val();

            if (device_name !== "") {
                if (device_id !== "") {
                    if (user_id !== "") {
                        moduleData={
                            device_id: device_id,
                            device_name: device_name,
                            user_id: user_id,
                        }
                        console.log(moduleData);
                        $.ajax({
                            url: base.baseUrl + "index.php/Home/Device/post_device",
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
                                status = (status == 1 ? 1 : 2);
                                layer.alert(res.mesg, {
                                    icon: status,
                                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                                })
                            }
                        })

                    } else {
                        layer.tips('你还没有选择领用者', '.layui-select-title', {
                            tips: [1, '#FF5722'] //还可配置颜色
                        });
                    }

                } else {
                    layer.tips('设备编号不能为空', '#device_id', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('设备名称不能为空', '#device_name', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }
        },
            this.reset = function () {
                $(".layui-form input").val("");
            }
    }


});
