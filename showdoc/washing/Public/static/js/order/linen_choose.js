/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";
layui.use(['jquery', 'layer', 'common', 'form', 'element', 'tree', 'laydate'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , laydate = layui.laydate
        , url = base.baseUrl
        , element = layui.element
        , common = layui.common
        , xtree = null;

        common.header();

    /*第一步:选择客户*/
    var one = $(".layui-timeline li:eq(0)"),
        two = $(".layui-timeline li:eq(1)"),
        three = $(".layui-timeline li:eq(2)"),
        fourth = $(".layui-timeline li:eq(3)");

    /*后台接受数据模型*/
    var objFormData = {
        customer_id: "",
        customer_name: "",
        department_id: "",
        department_name: "",
        start_time: "",
        end_time: ""
    };
    //选择客户
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
            //layer.msg('你当前选择了：' + custom.attr("title"));
            $(".layui-breadcrumb a:eq(1) cite").html(custom.attr("title"));

            /*隐藏自身 显示下一个*/
            one.find('.time-num').removeClass("active").html("&#xe618");
            one.find('.timeline-container').addClass("hide");

            two.find('.time-num').addClass("active");
            two.find('.timeline-container').removeClass("hide");

            /*隐藏的表单*/
            objFormData.customer_id = custom.val();
            objFormData.customer_name = custom.attr("title");

            $("#customer_data").val(custom.val());
            //获取库房
            getdepartment(custom.val());
        }
    });

    /*第二部*/
    $(".choose-dp").click(function (){
        var oCks = xtree.GetChecked(); //获取末级且选中的checkbox原dom对象，返回的类型:Array
        var res = '';
        for (var i = 0; i < oCks.length; i++) {
            res += oCks[i].value + ",";
        }
        if (res !== '') {
            $("#has-choose-dp").val(res);
            /*点击确定 走第一操作*/
            objFormData.department_id = res;
                // 隐藏自身
            two.find('.time-num').removeClass("active").html("&#xe618");
            two.find('.timeline-container').addClass("hide");

            three.find('.time-num').addClass("active");
            three.find('.timeline-container').removeClass("hide");

            getstorehouse();
        } else {
            layer.msg('您还没有选择部门');
        }
    });

    /*第4步*/
    $(".choose-end").click(function () {
            if( objFormData.start_time !== ""&&objFormData.end_time !== "")
            {
                // 收集所有的数据传输到下一个页面
                var type = base.getUrl().type;
                /*总月度*/
                if (/^detail$/.test(type)) {
                    /*发送请求*/
                    targetUrl(type);
                }
                /*月度出*/
                if (/^sender/.test(type)) {
                    targetUrl(type);
                }

                /*月度入*/
                if (/^recriver/.test(type)) {
                    targetUrl(type);
                }
               
                targetUrl(type);
            } else {
            var alert = layer.alert('亲，您还没有选择日期呢', {
                    icon: 2,
                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                }, function () {
                    layer.close(alert);
                }
            )
        
        }
    });

    /**
     * 获取部门
     * @param customer_id
     */
    function getdepartment(customer_id)
    {
        var ajaxNodes = [];
        var sendurl = url + "index.php/Home/Department/getdepartmentlist?customer_id="+customer_id;
        //var a1 = $.ajax({url: url + "index.php/Home/Department/get_department_list?customer_id="+customer_id, dataType: "json"});
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
        return false;
        $.when(a1).done(function (v1) {

            if (v1.result === 1) {
                ajaxNodes = v1.data;
                console.log(ajaxNodes);
                //创建tree
                /*layui.tree({
                    elem: '#ifuner_tree' //指定元素
                    // , target: '_blank' //是否新选项卡打开（比如节点返回href才有效）
                    , check: 'checkbox'
                    , click: function (item) { //点击节点回调
                        if (item.alias == 1) {
                            layer.msg("您当前选择" + item.name);

                            $("#has-choose-dp").val(item.name).prop('data-id', item.id);

                            objFormData.department_id = item.id;

                            objFormData.department_name = item.name;
                            $(".layui-breadcrumb a:eq(2) cite").html(item.name);
                        }
                    }
                    , nodes: ajaxNodes
                });*/
            } else {
                layer.msg("数据不正常，请联系相关技术人员处理");
            }
        });
    }

    /**
     * 获取库房
     */
    function getstorehouse()
    {
        var customer_id = $("#customer_data").val();
        var department_id = $("#has-choose-dp").val();

        $.ajax({
            url: url + "index.php/Home/Order/get_storehouse_list",
            type: "post",
            dataType: "json",
            data: {customer_id: customer_id, department_id: department_id},
            success: function (res) {
                console.log(res);
                if (res.result === 1) {
                    /*重置*/
                    var str = "";
                    $.each(res.data, function (i, v) {
                        str += '<div class="layui-col-xs4 layui-col-sm3 layui-col-md2 layui-col-lg1">' +
                            '<input type="checkbox" name="choose_storehouse[]" lay-skin="primary" title="'+ v.WAREHOUSE_NAME+'" checked="" value="'+ v.WAREHOUSE_ID +'">' +
                            ' </div>';
                    });
                    $("#storehouse_list").html(str);
                    form.render();
                }
            }
        });
        /*时间渲染*/
        laydate.render({
         elem: '#date'
         , range: true
         , done: function (value, date, endDate) {

         date = date.year + "-" + date.month + "-" + date.date;
         endDate = endDate.year + "-" + endDate.month + "-" + endDate.date;

         /*赋值给隐藏的表单*/
         objFormData.start_time = date;
         objFormData.end_time = endDate;
         }
         });
    }

    function get_time(str){

        var date = new Date(str);
        // 有三种方式获取
        var time = date.getTime();
        return time

    }

    function targetUrl(type) {
        var start_times = get_time(objFormData.start_time);
        var end_times = get_time(objFormData.end_time);
      
        if(start_times > end_times){
            layer.msg("选择时间异常");
            return false;
        }

        if((end_times-start_times)/(24*3600*1000) > 30){
            layer.msg("时间区间最多为30天");
            return false;
        }

        window.location.href = '/index.php/Home/Order/' + type + '_linen_month_order?customer_id=' +
            objFormData.customer_id + '&department_id=' +
            objFormData.department_id + '&start_time=' +
            objFormData.start_time + '&end_time=' +
            objFormData.end_time;
    }
});

