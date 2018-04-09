/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element', 'tree'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , common = layui.common
        , url = base.baseUrl
        , xtree = null;

     common.header();

    var moduleData = {};

    /*第一步:选择客户*/
    $(".choose-custom").click(function () {
        var custom = $(this).siblings(".layui-row").find("input[type=radio]:checked");

        moduleData.customer_id = custom.val();

        if (custom.length === 0) {
            var alert = layer.alert('亲，您还没有选择客户', {
                    icon: 2,
                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                }, function () {
                    layer.close(alert);
                }
            )
        } else {
            //console.log(custom.attr("title"));
            //layer.msg('你当前选择了：' + custom.attr("title"));
            $(".layui-breadcrumb a:eq(1) cite").html(custom.attr("title"));

            var one = $(".layui-timeline li:eq(0)"),
                two = $(".layui-timeline li:eq(1)");
            /*隐藏自身 显示下一个*/
            one.find('.time-num').removeClass("active").html("&#xe618");
            one.find('.timeline-container').addClass("hide");

            two.find('.time-num').addClass("active");
            two.find('.timeline-container').removeClass("hide");
        }
        //获取部门
        getdepartment(moduleData.customer_id);
        /*请求树节点*/

        /*var a1 = $.ajax({url: base.baseUrl + "index.php/Home/Inventory/get_department_list", dataType: "json",data:{customer_id:moduleData.customer_id}});
        $.when(a1).done(function (v1) {
            if (v1.result === 1) {
                console.log(v1);
                layui.tree({
                    elem: '#ifuner_tree' //指定元素

                    , click: function (item) { //点击节点回调

                        if (item.alias == 1) {
                            //layer.msg("你当前选择" + item.name);
                            console.log(item);
                            $(".layui-breadcrumb a:eq(2) cite").html(item.name);
                            moduleData.department_id = item.id;
                        }

                    }
                    , nodes: v1.data
                });
            } else {
                layer.msg("数据不正常，请联系相关技术人员处理");
            }
        });*/
    });


    /*选择确认键*/
    $(".choose-enter").click(function () {
        var oCks = xtree.GetChecked(); //获取末级且选中的checkbox原dom对象，返回的类型:Array
        var res = '';
        for (var i = 0; i < oCks.length; i++) {
            res += oCks[i].value + ",";
        }
        if (res == '') {
            layer.msg('您还没有选择部门');
        } else {
            moduleData.department_id = res;
        }
        /*手机表单数据*/
        var custom = $('#has-choose-custom');

        if ( moduleData.customer_id !== '' && moduleData.department_id !== '') {
            /*示例跳转数据*/
            window.location.href = '/index.php/Home/Inventory/inventory' + "?customer_id=" + moduleData.customer_id + "&department_id=" + moduleData.department_id;
        }
    });

    /**
     * 获取部门
     * @param customer_id
     */
    function getdepartment(customer_id) {
        var sendurl = url + "index.php/Home/Department/getdepartmentlist?customer_id=" + customer_id;
        xtree = new layuiXtree({
            elem: 'ifuner_tree'           //放xtree的容器，id，不要带#号（必填）
            , form: form                       //layui form对象 （必填）
            , data: sendurl//'server/XtreeData.ashx'    //服务端地址（必填）
            , isopen: true                     //初次加载时全部展开，默认true
            , color: "#000"                    //图标颜色
            , icon: {                          //图标样式 （必填，不填会出点问题）
                open: "&#xe7a0;"               //节点打开的图标
                , close: "&#xe622;"            //节点关闭的图标
                , end: "&#xe621;"              //末尾节点的图标
            }
        });
    }
});

