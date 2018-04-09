/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , url = base.baseUrl
        , common = layui.common;

     common.header();


    /*点击新增*/
    $(".add_part").on("click", function () {
        layer.prompt({title: '新增角色', formType: 3}, function (text, index) {
            $.ajax({
                url: url + "index.php/Home/User/post_role",
                type: "post",
                data: {role_name: text},
                dataType: "json",
                success: function (res) {
                    var status = res.result;

                    if (res.result === 1) {
                        window.location.reload();
                    }
                    status = (status == 1) ? 1 : 2;
                    layer.msg(res.mesg, {icon: status, time: 1500});

                    layer.close(index);
                }
            });

        });
    });

    /*点击删除*/
    $(".del").on("click", function () {
        layer.confirm('确认删除吗？', {
            btn: ['确认', '取消'] //按钮
        }, function () {
            layer.msg('确认操作', {icon: 1});
        }, function () {
            layer.msg('取消操作');
        });
    });

    /*编辑*/
    $(".edit").on("click", function () {
        var that = $(this);
        var eObj = {
            role_name: that.parent("td").siblings("td:eq(0)").html(),
            role_id: that.attr("data-id")
        }
        console.log(eObj);
        var str =
            ' <table class="layui-table" lay-even="" lay-skin="nob">' +
            '     <tbody>' +
            '     <tr>' +
            '     <td>角色名称</td>' +
            '     <td>' +
            '     <input type="text" id="new-part" name="title" lay-verify="title" value=' + eObj.role_name + ' autocomplete="off" placeholder="请输入角色名称" class="layui-input">' +
            '     </td>' +
            '     </tr>' +
            '     <tr>' +
            '     <td></td>' +
            '     <td>' +
            '      <button class="layui-btn confirm-edit">确定修改</button>' +
            '        <button class="layui-btn layui-btn-primary layui-layer-close">取消</button>' +
            '     </td>' +
            '     </tr>' +
            '     </tbody>' +
            '     </table>';

        var index = layer.open({
            title: "修改角色",
            type: 1,
            area: ['400px', '200px'], //宽高
            content: str
        });

        $(".confirm-edit").on("click", function () {
            eObj.role_name = $("#new-part").val();
            $.ajax({
                url: url + "index.php/Home/User/post_role",
                type: "post",
                data: eObj,
                dataType: "json",
                success: function (res) {
                    var status = res.result;


                    status = (status == 1) ? 1 : 2;
                    layer.msg(res.mesg, {icon: status, time: 1500});

                    layer.close(index);
                    if (res.result === 1) {
                        window.location.reload();
                    }
                }
            });
        });
    });

});
