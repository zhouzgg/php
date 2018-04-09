/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element', 'tree'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , common = layui.common;

     common.header();

    // 数据模型
    var moduleData = {
        customer_id: "",
        parent_id: "",
        department_name: "",
        department_contact_phone: "",
        department_contact_person: ""
    };

    var layuiForm = $(".layui-form")
        , customName = layuiForm.find("#custom-name")
        , firstDepart = layuiForm.find("#first-depart")
        , departname = layuiForm.find("#departname")
        , username = layuiForm.find("#username")
        , phone = layuiForm.find("#phone");

    var ifuner = new allFunc();
    /*新增*/
    $(".add-btn").click(function () {
        ifuner.add("index.php/Home/Department/post_department");
    })

    /*清空表单*/
    $(".reset-btn").click(function () {
        customName.val("");
        ifuner.reset();
    });

    function allFunc() {
        // 实例方法  /*type 为1是洗衣厂 2为新增客户*/
        this.add = function (url) {
            /*客户*/
            if (customName.val() !== "") {
                /*请选择上级部门*/
                if (firstDepart.val() !== "") {
                    /*部门名称*/
                    if (departname.val() !== "") {
                        /*联系人*/
                        if (username.val() !== "") {
                            /*联系方式*/
                            if (phone.val().length == 11) {

                                /*验证通过收集数据*/
                                moduleData = {
                                    customer_id: customName.find("option:selected").val(),
                                    parent_id: firstDepart.prop("name"),
                                    department_name: departname.val(),
                                    department_contact_phone: phone.val(),
                                    department_contact_person: username.val(),
                                };
                                console.log(moduleData);
                                /*发送请求*/
                                $.ajax({
                                    url: base.baseUrl + url,
                                    type: "post",
                                    dataType: "json",
                                    data: moduleData,
                                    success: function (res) {
                                        if (res.result === 1) {

                                            /*重置*/
                                            ifuner.reset();
                                        }
                                        layer.alert(res.mesg, {
                                            icon: 1,
                                            skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                                        })
                                    }
                                });

                            } else {
                                layer.tips('联系方式格式不正确', '#phone', {
                                    tips: [1, '#FF5722'] //还可配置颜色
                                });
                            }
                        } else {
                            layer.tips('联系人格式不正确', '#username', {
                                tips: [1, '#FF5722'] //还可配置颜色
                            });
                        }

                    } else {
                        layer.tips('部门名称格式不正确', '#departname', {
                            tips: [1, '#FF5722'] //还可配置颜色
                        });
                    }
                } else {
                    layer.tips('上级部门格式不正确', '#first-depart', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }

            } else {
                layer.tips('请选择客户', '.layui-select-title', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }

        },
            this.reset = function () {
                $(".layui-form input").val("");
            }

    }

    firstDepart.click(function () {

        var layuiForm = $(".layui-form")
            , customName = layuiForm.find("#custom-name")
            , customer_id = "";
        if (customName.val() == "") {
            layer.msg("请先选择客户");
            return false;
        } else {
            customer_id = customName.find("option:selected").val();
        }
        var index = layer.open({
            type: 1,
            skin: 'layui-layer-demo', //样式类名
            closeBtn: 1, //不显示关闭按钮
            title: '选择上级部门',
            anim: 2,
            maxmin: true, //开启最大化最小化按钮
            area: ['380px', '450px'],
            shadeClose: true, //开启遮罩关闭
            content: '<ul id="demo1" class="clearfix" style="padding: 20px;"></ul>',
        });

        /*请求树节点*/
        var ajaxNodes = [];
        var a1 = $.ajax({url: base.baseUrl + "index.php/Home/Department/get_department_list?customer_id="+customer_id, dataType: "json"});
        $.when(a1).done(function (v1) {
            console.log(v1);
            if (v1.result === 1) {
                ajaxNodes = v1.data;
                layui.tree({
                    elem: '#demo1' //指定元素
                    // , target: '_blank' //是否新选项卡打开（比如节点返回href才有效）

                    , click: function (item) { //点击节点回调
                        if (item.alias == 1) {
                            layer.msg("你当前选择" + item.name);
                            console.log(item);
                            firstDepart.val(item.name).prop('name', item.id);

                            layer.close(index);
                        }
                    }
                    , nodes: ajaxNodes
                });
            } else {
                layer.msg("数据不正常，请联系相关技术人员处理");
            }
        });
    });
    //搜索
    $(".search-btn").click(function () {
        var layuiForm = $(".layui-form")
            , customName = layuiForm.find("#custom-name")
            , customer_id = "";
        if (customName.val() == "") {
            return false;
        } else {
            customer_id = customName.find("option:selected").val();
        }
        var href = window.location.href.split('?');
        href = href[0];
        window.location.href = href + "?customer_id=" + customer_id
    });

});

