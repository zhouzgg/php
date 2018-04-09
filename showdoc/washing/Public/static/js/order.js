/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element', 'laydate'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , laydate = layui.laydate
        , url = base.baseUrl

        , element = layui.element
        , common = layui.common;

     common.header();
    //日期范围
    laydate.render({
        elem: '#check_data'
        , range: true
    });


    $(".d-order-details").click(function () {
        var dataId = $(this).attr("data-id");
        /*发送请求*/
        $.ajax({
            url: url + 'index.php/Home/Order/dirty_order_detail_details',
            type: "post",
            dataType: "json",
            data: {sub_id: dataId},
            success: function (res) {
                console.log(res);
                if (res.result === 1) {
                    /*重置*/
                    var str = "";
                    $.each(res.data, function (i, v) {
                        str += ' <tr>' +
                            ' <td>' + (i + 1) + '</td>' +
                            ' <td>' + v.RECEIPT_SUB_ID + '</td>' +
                            ' <td>' + v.BARCODE + '</td>' +
                            ' </tr>';
                    })

                    layer.open({
                        type: 1,
                        title: '货物详情',
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
                        '' + str + '' +
                        ' </tbody>' +
                        '</table>' +
                        '</div>'
                    });
                }
            }
        });

    })
});
