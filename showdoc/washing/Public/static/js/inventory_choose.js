/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'form', 'element', 'tree'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element;

    /*第一步:选择客户*/
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

            var one = $(".layui-timeline li:eq(0)"),
                two = $(".layui-timeline li:eq(1)");
            /*隐藏自身 显示下一个*/
            one.find('.time-num').removeClass("active").html("&#xe618");
            one.find('.timeline-container').addClass("hide");

            two.find('.time-num').addClass("active");
            two.find('.timeline-container').removeClass("hide");


            /*隐藏的表单*/
            $('#has-choose-custom').val(custom.attr("title"));
            // .prop('data-id', item.id);

        }
    })


    /*选择确认键*/
    $(".choose-enter").click(function () {
        /*手机表单数据*/
        var custom = $('#has-choose-custom'), dp = $('#has-choose-dp');

        if (custom.val() !== '' && dp.val() !== '') {

            layer.msg(custom.val() + "-----" + dp.val());

            /*跳转到详情页查询*/

            /*示例跳转数据*/
            window.location.href = '/index.php/Home/Inventory/inventory?cid=' + 1 + '&did=' + 2 + '';
        }
    })

    layui.tree({
        elem: '#ifuner_tree' //指定元素
        // , target: '_blank' //是否新选项卡打开（比如节点返回href才有效）

        , click: function (item) { //点击节点回调
            if (item.alias == 1) {
                layer.msg("你当前选择" + item.name);
                console.log(item);

                /*隐藏的表单*/
                $('#has-choose-dp').val(item.name).prop('data-id', item.id);

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

