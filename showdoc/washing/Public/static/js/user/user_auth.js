/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";
/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , form = layui.form
        , element = layui.element
        , url = base.baseUrl
        , common = layui.common;

     common.header();


    var setting = {
        treeId: "ztree",
        view: {
            selectedMulti: false
        },
        check: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        edit: {
            enable: false
        },
        callback: {
            onCheck: zTreeOnCheck,
            onNodeCreated: zTreeOnNodeCreated
            // beforeCheck: zTreeBeforeCheck
        },
        dataNodes: ""
    };

    function zTreeOnCheck(event, treeId, treeNode) {
        console.log(treeNode.tId + ", " + treeNode.name + "," + treeNode.checked);
        var treeObj = $.fn.zTree.getZTreeObj(treeId);
        setting.dataNodes = treeObj.getCheckedNodes(true);
    };

    function zTreeOnNodeCreated(event, treeId, treeNode) {
        var treeObj = $.fn.zTree.getZTreeObj(treeId);
        setting.dataNodes = treeObj.getCheckedNodes(true);
    };

    /*取地址栏参数渲染*/
    /*搜索显示地址参数*/
    var urlParam = base.getUrl();

    /*搜索框赋值*/
    if (!$.isEmptyObject(urlParam)) {
        $("#choose_part").find('option[value=' + urlParam.aid + ']').attr("selected", "true");
        getData(urlParam.aid);
        form.render();

    }else{
        getData("");
    }


    /*选择角色*/
    $(".layui-anim dd").click(function () {
        var Val = $(this).attr("lay-value");
        if (Val !== "") {
            getData(Val);
        }
    })

    function getData(Val) {
        $.ajax({
            url: url + "index.php/Home/User/get_role_auth",
            type: "post",
            data: {role_id: Val},
            dataType: "json",
            success: function (res) {
                var status = res.result;
                if (status === 1) {
                    $.fn.zTree.init($("#treeDemo"), setting, res.data);
                }
            }
        })
    }

    $(".save").click(function () {
        var Val = $("#choose_part option:selected").val();
        console.log((!!Val));
        if (!!Val) {
            var role_function = "";
            var str = ",";
            $.each(setting.dataNodes, function (i, v) {
                str = (i == 0) ? "" : ",";
                role_function += (str + v.id);
            });

            if (setting.dataNodes.length > 0) {
                $.ajax({
                    url: url + "index.php/Home/User/post_auth",
                    type: "post",
                    data: {role_id: Val, role_function: role_function},
                    dataType: "json",
                    success: function (res) {
                        var status = res.result;
                        status = (status == 1) ? 1 : 2;
                        layer.msg(res.mesg, {icon: status,time:1500});
                    }
                });

            } else {
                layer.msg("请选择权限");
            }

        } else {
            layer.msg("请先选择角色");
        }

    });


});
