/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element;


    /*点击新增*/
    $(".add_part").on("click", function () {
        layer.prompt({title: '新增角色', formType: 3}, function (text, index) {
            layer.close(index);
            layer.msg('新增角色！' + text);
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
        var eObj = {
            eName: $(this).parent("td").siblings("td:eq(0)").html(),
            eId: '1'
        }
        var str =
            ' <table class="layui-table" lay-even="" lay-skin="nob">' +
            '     <tbody>' +
            '     <tr>' +
            '     <td>角色名称</td>' +
            '     <td>' +
            '     <input type="text" name="title" lay-verify="title" value=' + eObj.eName + ' autocomplete="off" placeholder="请输入角色名称" class="layui-input">' +
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
            type: 1,
            area: ['400px', '200px'], //宽高
            content: str
        });

        $(".confirm-edit").on("click", function () {
            layer.confirm('确认修改吗？', {
                btn: ['确认', '取消'] //按钮
            }, function () {
                layer.msg('确认操作', {icon: 1});
                layer.close(index);

            }, function () {
                layer.msg('取消操作');
            });
        });
    });

});
