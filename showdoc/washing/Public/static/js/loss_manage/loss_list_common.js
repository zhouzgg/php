/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , element = layui.element
        , common = layui.common;

    common.header();

    /*取地址栏参数*/
    var urlParam = base.getUrl();
    /*搜索框赋值*/
    if (!$.isEmptyObject(urlParam)) {
        $('#choose-custom option[value=' + urlParam.customer_id + ']').attr("selected", "true");
        $('#status option[value=' + urlParam.status + ']').attr("selected", "true");
        $('#person-name').val(urlParam.name)
        form.render();
    }


    var href = window.location.href.split('?');
    href = href[0];
    /*立即搜索*/
    $(".search-btn").click(function () {
        /*获取选择的状态和申报人*/
        var status = $('#status').find("option:selected").val()
            , personName = $("#person-name").val()
            , chooseCustom = $("#choose-custom").val()

        if (chooseCustom !== "") {
            if (status !== "-1") {
                if (personName != "") {
                    console.log(personName);
                    console.log(status);
                    var choose_status;
                    if (status == "") {
                        choose_status = "";
                    } else {
                        choose_status = '&status=' + status + '';
                    }

                    window.location.href = href + "?name=" + personName + "&customer_id=" + chooseCustom + choose_status;
                } else {
                    layer.tips('请输入申报人姓名', '#person-name', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('请选择状态', '.status', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }

        } else {
            layer.tips('请选择客户', '.choose-custom', {
                tips: [1, '#FF5722'] //还可配置颜色
            });
        }


    });
});
