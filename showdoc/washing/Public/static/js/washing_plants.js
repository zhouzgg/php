/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element', 'upload'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , upload = layui.upload;

    window.ifuner = layui.common;
    ifuner.hello(); //弹出Hello World!

    // 定义数据模型
    var moduleData = {}

    var layuiForm = $(".layui-form")
        , title = layuiForm.find("#title")
        , address = layuiForm.find("#address")
        , username = layuiForm.find("#username")
        , phone = layuiForm.find("#phone");

    function allFunc() {
        // 实例方法
        this.add = function () {
            /*名称*/
            if (title.val() !== "") {
                /*地址*/
                if (address.val() !== "") {
                    /*用户*/
                    if (username.val() !== "") {
                        /*手机号*/
                        if (phone.val().length==11) {

                            if (moduleData.washing_logo !== "") {

                                /*数据赋值收集*/
                                moduleData = {
                                    washing_name: title.val(),
                                    washing_address: address.val(),
                                    washing_contact_person: username.val(),
                                    washing_contact_phone: phone.val(),
                                    washing_logo: moduleData.washing_logo
                                }

                                $.ajax({
                                    url: base.baseUrl + "index.php/Home/WashingPlants/post_washing_plant",
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
                                layer.msg("亲，你还没有上传logo哦！");
                            }
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
                } else {
                    layer.tips('地址格式不正确', '#address', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }

            } else {
                layer.tips('名称格式不正确', '#title', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }

        },
            this.reset = function () {
                $(".layui-form input").val("");
                $(".showLogo").hide().find("img").attr("src", "");
                moduleData.washing_logo = "";
            },
            this.remove = function () {
                $(".showLogo").hide().find("img").attr("src", "");
                moduleData.washing_logo = "";
                layer.msg("删除成功");
            }
    }

    //文件上传
    upload.render({
        elem: '#test7'
        /*文康后台接口*/
        , url: base.baseUrl + 'index.php/Home/File/uploadLogo'
        , size: 2048 //限制文件大小，单位 KB
        , done: function (res) {
            if (res.result === 1) {
                moduleData.washing_logo = res.data.logo_file;
                $(".showLogo").show().find("img").attr("src", res.data.logo_file);
            }
            layer.msg(res.mesg);
        }
    });

});
