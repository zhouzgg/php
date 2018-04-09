/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element', 'tree'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , url = base.baseUrl
        , common = layui.common;


     common.header();

    $(".see-more").click(function () {
        var str = "";
        var dataId = $(this).attr("data-id");
        $.ajax({
            url: url + "index.php/Home/Inventory/inventory_detail_detail",
            type: "post",
            dataType: "json",
            data: {sub_id: dataId},
            success: function (res) {

                $.each(res.data, function (i, v) {
                    str += ' <tr>' +
                        ' <td>' + (i + 1) + '</td>' +
                        ' <td>' + v.RECEIPT_SUB_ID + '</td>' +
                        ' <td>' + v.BARCODE + '</td>' +
                        ' </tr>';
                });

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
                    '' + str +
                    ' </tbody>' +
                    '</table>' +
                    '</div>'
                });
            }
        });
    })
});

