/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['layer', 'common', 'form', 'element', 'tree'], function () {
    var layer = layui.layer
        , form = layui.form
        , element = layui.element
        , url = base.baseUrl
        , common = layui.common;

     common.header();

    /*树菜单结构*/
    var setting = {
        async: {
            enable: true,
            url: "/index.php/Home/Classify/getClassifyList",
            autoParam: ["id", "name=n", "level=lv"],
            otherParam: {"otherParam": "zTreeAsyncTest"},
            dataFilter: filter
        },
        view: {
            selectedMulti: false,
            expandSpeed: "",
        },
        edit: {
            enable: true
        },
        data: {
            simpleData: {
                enable: true
            }
        },
        callback: {
            beforeRename: beforeRename,
            beforeDrop: zTreeBeforeDrop
        }
    };

    /*拖拽*/
    // function zTreeBeforeDrop(treeId, treeNodes, targetNode, moveType) {
    //     console.log(treeNodes);
    //     console.log(targetNode);
    //     // if (!(targetNode == null || (moveType != "inner" && !targetNode.parentTId))) {
    //     //     console.log("if");
    //     //     ajaxData({line_id: treeNodes[0].id, parent_id: targetNode.id, line_name: ""});
    //     // } else {
    //     //     console.log("else");
    //     //     ajaxData({line_id: treeNodes[0].id, parent_id: targetNode.parent_id, line_name: ""});
    //     // }
    // };

    function zTreeBeforeDrop(treeId, treeNodes, targetNode, moveType)  {
        console.log(event);
        console.log(treeNodes);
        ajaxData({line_id: treeNodes[0].id, parent_id: targetNode.id, line_name: ""});
    };

    function filter(treeId, parentNode, childNodes) {

        childNodes = childNodes.data;
        if (!childNodes) return null;
        for (var i = 0, l = childNodes.length; i < l; i++) {
            childNodes[i].name = childNodes[i].name.replace(/\.n/g, '.');
        }
        return childNodes;
    }

    function beforeRename(treeId, treeNode, newName) {
        if (newName.length == 0) {
            setTimeout(function () {
                var zTree = $.fn.zTree.getZTreeObj("treeDemo");
                zTree.cancelEditName();
                layer.alert('节点名称不能为空.', {icon: 5});
            }, 0);
            return false;
        } else {
            console.log(treeId, treeNode, newName);

            ajaxData({line_id: treeNode.id, parent_id: treeNode.parent_id, line_name: newName});
            console.log(1);
        }
    }

    function ajaxData(obj) {
        $.ajax({
            url: url + "index.php/Home/Classify/editClassify",
            type: "post",
            data: obj,
            dataType: "json",
            success: function (res) {
                console.log(res);
                var status = res.result;
                status = (status == 1 ? 1 : 2);
                layer.msg(res.mesg, {icon: status, time: 1000,}, function () {
                    layer.closeAll();
                });
            }
        })
    }

    $(document).ready(function () {
        $.fn.zTree.init($("#treeDemo"), setting);
    });
});
