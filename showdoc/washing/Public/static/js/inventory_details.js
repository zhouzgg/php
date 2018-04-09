/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'form', 'element', 'tree'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element;

    $(".see-more").click(function () {
        layer.open({
            type: 1,
            title: '盘点详情',
            area: ['600px', '360px'],
            shadeClose: true, //点击遮罩关闭
            content: '<div style="padding: 20px;">' +
            '<table class="layui-table">' +
            ' <thead>' +
            ' <tr>' +
            ' <th>序号</th>' +
            ' <th>子订单编号</th>' +
            ' <th>芯片编号</th>' +
            ' </tr>' +
            ' </thead>' +
            ' <tbody>' +
            ' <tr>' +
            ' <td>1</td>' +
            ' <td>XX001</td>' +
            ' <td>FAG9768T6111</td>' +
            ' </tr>' +
            ' <tr>' +
            ' <td>1</td>' +
            ' <td>XX001</td>' +
            ' <td>FAG9768T6111</td>' +
            ' </tr>' +
            ' <tr>' +
            ' <td>1</td>' +
            ' <td>XX001</td>' +
            ' <td>FAG9768T6111</td>' +
            ' </tr>' +
            ' </tbody>' +
            '</table>' +
            '</div>'
        });
    })
});

