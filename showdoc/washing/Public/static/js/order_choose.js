/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

function getUrl() {
    var url = window.location.search;
    var theRequest = new Object();
    if (url.indexOf("?") != -1) {
        var str = url.substr(1);
        var strs = str.split("&");
        for (var i = 0; i < strs.length; i++) {
            theRequest[strs[i].split("=")[0]] = unescape(strs[i].split("=")[1]);
        }
    }
    return theRequest;
}

layui.use(['jquery', 'layer', 'form', 'element', 'tree', 'laydate'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , laydate = layui.laydate;

    /*第一步:选择客户*/
    var one = $(".layui-timeline li:eq(0)"),
        two = $(".layui-timeline li:eq(1)"),
        three = $(".layui-timeline li:eq(2)");
    var objFormData = {}
    $(".choose-custom").click(function () {
        var custom = $(this).siblings(".layui-row").find("input[type=radio]:checked");

        if (custom.length === 0) {
            var alert = layer.alert('亲，你还没有选择客户', {
                    icon: 2,
                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                }, function () {
                    layer.close(alert);
                }
            )
        } else {
            console.log(custom.attr("title"));
            layer.msg('你当前选择了：' + custom.attr("title"));
            $(".layui-breadcrumb a:eq(1) cite").html(custom.attr("title"));

            /*隐藏自身 显示下一个*/
            one.find('.time-num').removeClass("active").html("&#xe618");
            one.find('.timeline-container').addClass("hide");

            two.find('.time-num').addClass("active");
            two.find('.timeline-container').removeClass("hide");

            /*隐藏的表单*/
            $('#has-choose-custom').val(custom.attr("title"));
            objFormData.custom = custom.attr("title");
            // .prop('data-id', item.id);
        }
    });

    /*第二部*/
    $(".choose-dp").click(function () {
        if ($("#has-choose-dp").val() !== '') {

            /*点击确定 走第一操作*/
            // 隐藏自身

            two.find('.time-num').removeClass("active").html("&#xe618");
            two.find('.timeline-container').addClass("hide");

            three.find('.time-num').addClass("active");
            three.find('.timeline-container').removeClass("hide");

        } else {
            layer.msg('你还没有选择部门');
        }

    })


    /*选择确认键*/
    $(".choose-enter").click(function () {
        /*手机表单数据*/
        var customVal = $('#has-choose-custom'),
            dpVal = $('#has-choose-dp'),
            store_houseVal = $('#store_house'),
            date_chooseVal = $('#date_choose'),
            store_house = $(this).siblings(".layui-row").find("input[type=radio]:checked");

        if (store_house.length === 0) {
            var alert = layer.alert('亲，你还没有选择库房', {
                    icon: 2,
                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                }, function () {
                    layer.close(alert);
                }
            )
        } else {
            $(".layui-breadcrumb a:eq(3) cite").html(store_house.attr("title"));
            /*日期控件隐藏的表单不能为空*/
            if (date_chooseVal.val() !== "") {

                /*隐藏自身 显示下一个*/
                two.find('.time-num').removeClass("active").html("&#xe618");
                two.find('.timeline-container').addClass("hide");

                three.find('.time-num').addClass("active");
                three.find('.timeline-container').removeClass("hide");

                /*隐藏的表单*/
                $('#store_house').val(store_house.attr("title"));
                objFormData.store_house = store_house.attr("title");
                // 收集所有的数据传输到下一个页面
                console.log(objFormData);
                layer.msg("收集数据展示" + JSON.stringify(objFormData));

                var type = getUrl().type;
                window.location.href = '/index.php/Home/Order/' + type + '_order?d=&c&b=&d';
            } else {
                var alert = layer.alert('亲，你还没有选择日期呢', {
                        icon: 2,
                        skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                    }, function () {
                        layer.close(alert);
                    }
                )
            }
        }
    })

    /*时间渲染*/
    laydate.render({
        elem: '#date'
        , range: true
        , done: function (value, date, endDate) {
            console.log(value); //得到日期生成的值，如：2017-08-18
            console.log(date); //得到日期时间对象：{year: 2017, month: 8, date: 18, hours: 0, minutes: 0, seconds: 0}
            console.log(endDate); //得结束的日期时间对象，开启范围选择（range: true）才会返回。对象成员同上。

            /*赋值给隐藏的表单*/
            $("#date_choose").val(value);
            objFormData.date = value;
        }
    });

    layui.tree({
        elem: '#ifuner_tree' //指定元素
        // , target: '_blank' //是否新选项卡打开（比如节点返回href才有效）

        , click: function (item) { //点击节点回调
            if (item.alias == 1) {
                layer.msg("你当前选择" + item.name);
                console.log(item);

                /*隐藏的表单*/
                $("#has-choose-dp").val(item.name).prop('data-id', item.id);

                objFormData.dp = item.name;
                // layer.close(index);
                $(".layui-breadcrumb a:eq(2) cite").html(item.name);
            }
        }

        /*mock 假数据*/
        , nodes: [ //节点
            {
                name: '部门1'
                , id: 1
                , children: [
                {
                    name: '部门1-1'
                    , id: 11
                    , alias: 1
                }, {
                    name: '部门1-2'
                    , id: 12
                    , alias: 1
                }, {
                    name: '部门1-3'
                    , id: 13
                    , alias: 1
                }]
            },
            {
                name: '部门2'
                , id: 1
                , children: [
                {
                    name: '部门1-1'
                    , id: 11
                    , alias: 1
                }, {
                    name: '部门1-2'
                    , id: 12
                    , alias: 1
                }, {
                    name: '部门1-3'
                    , id: 13
                    , alias: 1
                }]
            },
            {
                name: '部门3'
                , id: 1
                , children: [
                {
                    name: '部门1-1'
                    , id: 11
                    , alias: 1
                }, {
                    name: '部门1-2'
                    , id: 12
                    , alias: 1
                }, {
                    name: '部门1-3'
                    , id: 13
                    , alias: 1
                }]
            },
            {
                name: '部门3'
                , id: 1
                , children: [
                {
                    name: '部门1-1'
                    , id: 11
                    , alias: 1
                }, {
                    name: '部门1-2'
                    , id: 12
                    , alias: 1
                }, {
                    name: '部门1-3'
                    , id: 13
                    , alias: 1
                }]
            },
            {
                name: '部门3'
                , id: 1
                , children: [
                {
                    name: '部门1-1'
                    , id: 11
                    , alias: 1
                }, {
                    name: '部门1-2'
                    , id: 12
                    , alias: 1
                }, {
                    name: '部门1-3'
                    , id: 13
                    , alias: 1
                }]
            },
            {
                name: '部门3'
                , id: 1
                , children: [
                {
                    name: '部门1-1'
                    , id: 11
                    , alias: 1
                }, {
                    name: '部门1-2'
                    , id: 12
                    , alias: 1
                }, {
                    name: '部门1-3'
                    , id: 13
                    , alias: 1
                }]
            }
        ]

    });

});

