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

    /*搜索显示地址参数*/
    var urlParam = base.getUrl();
    /*搜索框赋值*/
    if (!$.isEmptyObject(urlParam)) {
        $("#device_id").val(urlParam.device_no)
        $("#device_name").val(urlParam.device_name)
        $("#receiver").val(urlParam.user_name)
    }


    $(".search-btn").click(function () {
        var device_no = $("#device_id").val()
            , device_name = $("#device_name").val()
            , user_name = $("#receiver").val();

        moduleData = {
            device_name: device_name,
            device_no: device_no,
            user_name: user_name
        }
        var href = window.location.href.split('?');
        href = href[0];
        window.location.href = href + "?device_name=" + moduleData.device_name + "&device_no=" + moduleData.device_no + "&user_name=" + moduleData.user_name
    });

});
