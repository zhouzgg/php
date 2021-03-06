/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , common = layui.common;

     common.header();

    $(".del").click(function () {
        layer.confirm('确认删除该数据？', {
            title: "警告",
            btn: ['确认', '取消'] //按钮
        }, function () {

            layer.msg('确认', {icon: 1});
        }, function () {

            layer.msg('取消', {icon: 2});
        });
    });

    $(".edit").click(function () {
        var td = $(this).parent("td");
        var obj = {
            driver: td.siblings("td:eq(1)").text(),
            tel: td.siblings("td:eq(2)").text(),
        }

        layer.open({
            type: 1,
            title: "编辑司机信息",
            area: ['360px', '320px'], //宽高
            content: '<div style="padding:10px 20px;">' +
            '<table class="layui-table car-pop-edit">' +
            '<tbody>' +
            '<tr>' +
            '<td>司机姓名</td>' +
            '<td><input type="text" value=' + obj.driver + '></td>' +
            '</tr>' +
            '<tr>' +
            '<td>车牌号</td>' +
            '<td><input type="text" value=' + obj.tel + '></td>' +
            '</tr>' +
            '<tr>' +
            '<td colspan=2>' +
            '<button class="layui-btn layui-btn-radius save">确认保存</button>' +
            '<button class="layui-btn layui-btn-radius layui-btn-danger layui-layer-close">取消返回</button>' +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>' +
            '</div>'
        });
    });

    /*点击保存*/
    $("body").on("click",".save",function () {
        layer.msg('发送ajax请求，，，，编辑成功', {icon: 1},function () {
            layer.closeAll();
        });
    })
   

});
