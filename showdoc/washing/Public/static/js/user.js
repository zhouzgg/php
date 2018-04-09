/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , common = layui.common;

     common.header();

    /*删除弹窗*/
    $(".layui-table").on("click", ".del", function () {
        layer.confirm('确认删除该用户', {
            btn: ['删除', '取消'] //按钮
        }, function () {
            layer.msg('删除操作', {icon: 1});
        }, function () {
            layer.msg('取消操作', {});
        });
    });


    /*编辑*/
    $(".layui-table").on("click", ".edit", function () {
        var str =
       ' <table class="layui-table" lay-even="" lay-skin="nob">'+
       '     <tbody>'+
       '     <tr>'+
       '     <td>用户名</td>'+
       '     <td>'+
       '     <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入用户名" class="layui-input">'+
       '     </td>'+
       '     </tr>'+
       '     <tr>'+
       '     <td>姓名</td>'+
       '     <td>'+
       '     <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入用户名" class="layui-input">'+
       '     </td>'+
       '     </tr>'+
       '     <tr>'+
       '     <td>手机号码</td>'+
       '     <td>'+
       '     <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入用户名" class="layui-input">'+
       '     </td>'+
       '     </tr>'+
       '     <tr>'+
       '     <td>其他</td>'+
       '     <td>'+
       '     <input type="text" name="title" lay-verify="title" autocomplete="off" placeholder="请输入用户名" class="layui-input">'+
       '     </td>'+
       '     </tr>'+
       '     <tr>'+
       '     <td></td>'+
       '     <td>' +
       '      <button class="layui-btn">确定修改</button>' +
       '        <button class="layui-btn layui-btn-primary layui-layer-close">取消</button>'+
       '     </td>'+
       '     </tr>'+
       '     </tbody>'+
       '     </table>';

        layer.open({
            type: 1,
            skin: 'layui-layer-rim', //加上边框
            area: ['420px', '400px'], //宽高
            content: str
        });
    });
});
