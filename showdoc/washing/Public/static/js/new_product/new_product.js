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

    /*搜索显示地址参数*/
    var urlParam = base.getUrl();

    /*渲染 选择洗涤厂*/
    var washing_store = $("#check_washing_store");
    $.ajax({
        url: url + "index.php/Home/NewProduct/get_washinglist",
        type: "post",
        dataType: "json",
        success: function (res) {
            if (res.result === 1) {
                console.log(res.data);
                $.each(res.data, function (i, v) {
                    washing_store.find("option:lt(1)").after('<option value=' + v.WAREHOUSE_ID + '>' + v.WAREHOUSE_NAME + '</option>');
                    form.render();
                })


                /*搜索框赋值*/
                if(!$.isEmptyObject(urlParam)){
                    console.log($('#check_washing_store option[value=' + urlParam.washing_id + ']'));
                    $('#check_washing_store option[value=' + urlParam.washing_id + ']').attr("selected", "true");
                    $("#check_data").val(urlParam.start_time + " - " + urlParam.end_time);
                    form.render();
                    moduleData.time=[urlParam.start_time, urlParam.end_time];
                    /*输入框*/
                }else{

                }
            }
        }
    });

    var moduleData = {
        storehouse: "",
        time: [],
    }

    //日期范围
    laydate.render({
        elem: '#check_data'
        , range: true
        , done: function (value, date, endDate) {
            date = date.year + "-" + date.month + "-" + date.date;
            endDate = endDate.year + "-" + endDate.month + "-" + endDate.date;
            moduleData.time = [date, endDate];
            console.log(value);

        }
    });

    /*立即查询*/
    $(".search-btn").click(function () {
        if (washing_store.find("option:selected").val() !== "") {
            if (moduleData.time.length > 1) {

                moduleData.storehouse = washing_store.find("option:selected").val();
                console.log(moduleData);
                var href = window.location.href.split('?');
                href = href[0];
                console.log(href);
                window.location.href = href + "?washing_id=" + moduleData.storehouse + "&start_time=" + moduleData.time[0] + "&end_time=" + moduleData.time[1];
            } else {
                layer.tips('请选择洗涤厂日期范围', '#check_data', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }
        } else {
            layer.tips('请选择洗涤厂', '.layui-select-title', {
                tips: [1, '#FF5722'] //还可配置颜色
            });
        }
    })

    /*清除搜索条件*/
    $(".clear-btn").click(function () {
        $('.layui-form input').val("");

        /*时间制空*/
        moduleData.time = [];
    });


    $(".sld_see-more").click(function () {
        var str = "";
        var dataId = $(this).attr("data-id");
        $.ajax({
            url: url + "index.php/Home/NewProduct/newproduct_detail_detail",
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
                    '' + str +
                    ' </tbody>' +
                    '</table>' +
                    '</div>'
                });
            }
        });

    })
});
