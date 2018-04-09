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

    var ifuner = new allFunc(),
        moduleData = {},
        cname = $("#cname"),
        custom = $("#choose-custom"),
        parent = $("#parent-cname");

    var a1Module = {
        data: [
            {
                LINEN_ID: "",
                LINEN_NAME: ""
            }
        ],
        mesg: "",
        result: "",
    };

    /*新增*/
    $(".add-btn").click(function () {
        ifuner.add();
    });

    /*清空表单*/
    $(".reset-btn").click(function () {
        ifuner.reset();
    });
    /*客户选择*/
    form.on("select(customfilter)", function(data){
        var layuiForm = $(".layui-form")
            , customName = layuiForm.find("#choose-custom")
            , customer_id = "";
        if (customName.val() == "") {
            layer.msg("请先选择客户");
            return false;
        } else {
            customer_id = customName.find("option:selected").val();
        }

        if(customer_id != "")
        {
            var a2 = $.ajax({url: base.baseUrl + "index.php/Home/Classify/getClassifyByCustomer?customer_id="+customer_id, dataType: "json"});
            /*渲染表单*/
            $.when(a2).done(function(v2) {
                a1Module = v2;
                if (a1Module.result === 1) {
                    var _htmls = "";
                    $.each(a1Module.data, function (i, v) {
                        _htmls += '<option value=' + v.LINEN_ID + '>' + v.LINEN_NAME + '</option>';
                        //depart.find("option:lt(1)").after('<option value=' + v.DEPARTMENT_ID + '>' + v.DEPARTMENT_NAME + '</option>')
                    });
                    $("#parent-cname").html(_htmls);
                    /*重新渲染表单*/
                    form.render();
                }
            });
        }
    });

    function allFunc() {
        // 实例方法
        this.add = function () {
            if(custom.val() === "")
            {
                layer.msg("请选择客户！");
                return false;
            }
            if(parent.val() === "")
            {
                layer.msg("请选择上级分类！");
                return false;
            }
            if (cname.val() !== "") {
                /*数据赋值收集*/
                moduleData = {
                    parent_id: $("#parent-cname option:selected").val(),
                    customer_id: $("#choose-custom option:selected").val(),
                    cname: cname.val(),
                };
                //console.log(moduleData);
                $.ajax({
                    url: base.baseUrl + "index.php/Home/Classify/post_classify",
                    type: "post",
                    dataType: "json",
                    data: moduleData,
                    success: function (res) {
                        //console.log(res);
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
                layer.msg("分类名称不能为空！");
                return false;
            }
        },
        this.reset = function () {
            $(".layui-form input").val("");
        }
    }
});
