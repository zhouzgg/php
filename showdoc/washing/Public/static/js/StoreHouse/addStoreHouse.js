/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , url = base.baseUrl
        , element = layui.element
        , common = layui.common;

    common.header();
    var custom = $("#custom")
        , depart = $("#depart")
        , title = $("#title")
        , address = $("#address")
        , username = $("#username")
        , phone = $("#phone");

    var a1 = $.ajax({url: url + "index.php/Home/StoreHouse/customer_name_list", dataType: "json"});

    var a1Module = {
        data: [
            {
                WAREHOUSE_ID: "",
                WAREHOUSE_NAME: ""
            }
        ],
        mesg: "",
        result: "",
    };

    var a2Module = {
        data: [
            {
                DEPARTMENT_ID: "",
                DEPARTMENT_NAME: ""
            }
        ],
        mesg: "",
        result: "",
    };

    /*渲染表单*/
    $.when(a1).done(function (v1) {
        //console.log(v1[0]);
        a1Module = v1;
        if (a1Module.result === 1) {
            $.each(a1Module.data, function (i, v) {
                custom.find("option:lt(1)").after('<option value=' + v.WAREHOUSE_ID + '>' + v.WAREHOUSE_NAME + '</option>')
            });

            /*重新渲染表单*/
            form.render();
        }
    });

    //客户选择
    form.on("select(customfilter)", function(data){
        var layuiForm = $(".layui-form")
            , customName = layuiForm.find("#custom")
            , customer_id = "";
        if (customName.val() == "") {
            layer.msg("请先选择客户");
            return false;
        } else {
            customer_id = customName.find("option:selected").val();
        }

        if(customer_id != "")
        {
            var a2 = $.ajax({url: url + "index.php/Home/StoreHouse/department_name_list?customer_id="+customer_id, dataType: "json"});
            /*渲染表单*/
            $.when(a2).done(function(v2) {
                a2Module = v2;
                if (a2Module.result === 1) {
                    var _htmls = "";
                    $.each(a2Module.data, function (i, v) {
                        _htmls += '<option value=' + v.DEPARTMENT_ID + '>' + v.DEPARTMENT_NAME + '</option>';
                        //depart.find("option:lt(1)").after('<option value=' + v.DEPARTMENT_ID + '>' + v.DEPARTMENT_NAME + '</option>')
                    });
                    depart.html(_htmls);
                    /*重新渲染表单*/
                    form.render();
                }
            });
        }
    });

    var moduleData = {};
    var ifuner = new allFunc();
    $(".add-btn").click(function () {
        ifuner.add();
    });

    $(".reset-btn").click(function () {
        custom.val("");
        depart.val("");
        ifuner.reset();
    });

    function allFunc() {
        // 实例方法
        this.add = function () {
            if (custom.val() !== "") {
                if (depart.val() !== "") {
                    /*名称*/
                    if (title.val() !== "") {
                        /*地址*/
                        if (address.val() !== "") {
                            /*用户*/
                            if (username.val() !== "") {
                                /*手机号*/
                                if (phone.val().length == 11) {
                                    /*数据赋值收集*/
                                    moduleData = {
                                        customer_id: custom.find("option:selected").val(),
                                        department_id: depart.find("option:selected").val(),
                                        department_name: title.val(),
                                        department_address: address.val(),
                                        department_contact_person: username.val(),
                                        department_contact_phone: phone.val()
                                    };

                                    $.ajax({
                                        url: url + "index.php/Home/StoreHouse/post_storehouse",
                                        type: "post",
                                        dataType: "json",
                                        data: moduleData,
                                        success: function (res) {
                                            //console.log(res);
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
                } else {
                    layer.tips('请选择管理部门', '.depart', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('请选择客户', '.custom', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }

        },
            this.reset = function () {
                $(".layui-form input").val("");
            }
    }
});
