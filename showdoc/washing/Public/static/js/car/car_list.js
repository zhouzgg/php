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

    var obj = {};

    $(".edit").click(function () {
        var td = $(this).parent("td");

        obj = {
            car_name: td.siblings("td:eq(1)").text(),
            car_phone: td.siblings("td:eq(2)").text(),
        }

        var diverId = $(this).attr("data-id");

        obj.car_id = diverId;

        layer.open({
            type: 1,
            title: "编辑司机信息",
            area: ['360px', '320px'], //宽高
            content: '<div style="padding:10px 20px;">' +
            '<table class="layui-table car-pop-edit">' +
            '<tbody>' +
            '<tr>' +
            '<td>车辆名称</td>' +
            '<td><input type="text" id="car_name" maxlength=11 value=' + obj.car_name + '></td>' +
            '</tr>' +
            '<tr>' +
            '<td>车牌号</td>' +
            '<td><input type="text" maxlength=7 id="car_phone" value=' + obj.car_phone + '></td>' +
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
    $("body").on("click", ".save", function () {
        obj.car_name = $("#car_name").val();
        obj.car_phone = $("#car_phone").val();

        console.log(obj);

        $.ajax({
            url: base.baseUrl + "index.php/Home/Car/post_car",
            type: "post",
            dataType: "json",
            data: obj,
            success: function (res) {
                if (res.result === 1) {
                    window.location.reload();
                }
                var status = res.result;
                status = (status == 1 ? 1 : 2);
                layer.msg(res.mesg, {icon: status}, function () {
                    layer.closeAll();
                });
            }
        })

    })


});
