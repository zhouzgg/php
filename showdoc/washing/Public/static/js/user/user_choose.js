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


    /*选择分类*/
    var first = $(".layui-timeline li:eq(0)");
    var last = $(".layui-timeline li:eq(1)");
    var userPart = {}

    $('.choose-cata').click(function () {
        userPart.cata = $(this).siblings(".layui-row").find('input:checked').val();
        if (userPart.cata !== undefined) {
            if (userPart.cata === "admin") {
                window.location.href = "/index.php/Home/User/user_add?cid=" + userPart.cata;
            } else {
                first.find(".time-num").removeClass("active");
                first.find(".timeline-container").addClass("hide");

                last.find(".timeline-container").removeClass("hide");
                last.find(".time-num").addClass("active");
            }
        } else {
            layer.msg('请选择用户类别');
        }
    })

    /*选择身份*/
    $('.choose-id').click(function () {
        userPart.part = $(this).siblings(".layui-row").find('input:checked').val();
        if (userPart.part !== undefined) {
            window.location.href = '/index.php/Home/User/user_add?cid=' + userPart.cata + '&did=' + userPart.part;
        } else {
            layer.msg('请选择用户身份');
        }
    })
});
