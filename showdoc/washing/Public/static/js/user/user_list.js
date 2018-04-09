/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , element = layui.element
        , common = layui.common
        , url = base.baseUrl;
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

    var username = $("#username")
        , personType = $("#person-type");


    /*后台接受数据模型*/
    var moduleData = {
        user_name: "",
        role_id: ""
    }

    /*搜索显示地址参数*/
    var urlParam = base.getUrl();

    /*搜索框赋值*/
    if (!$.isEmptyObject(urlParam)) {
        username.val(urlParam.user_name);
        personType.find('option[value=' + urlParam.role_id + ']').attr("selected", "true");
        form.render();
    }

    $(".search-btn").on("click", function () {
        if (username.val() !== "") {
            if (personType.val() !== "") {
                moduleData = {
                    user_name: username.val(),
                    role_id: personType.val()
                }
                console.log(moduleData);
                var href = window.location.href.split('?');
                href = href[0];
                window.location.href = href + "?user_name=" + moduleData.user_name + "&role_id=" + moduleData.role_id;
            } else {
                layer.tips('请选择身份', '.layui-select-title', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }
        } else {
            layer.tips('用户格式不正确', '#username', {
                tips: [1, '#FF5722'] //还可配置颜色
            });
        }
    })

    /*编辑*/
    $(".layui-table").on("click", ".edit", function () {
        var that = $(this)
            , dataId = that.parent("td").attr("data-id")
            , roleId = that.attr("role-id")
            , tr = that.parent("td").parent("tr")
        var moduluData = {
            user_id: dataId,
            user_name: tr.find("td:eq(1)").text(),
            true_name: tr.find("td:eq(2)").text(),
            collphone: tr.find("td:eq(4)").text(),
            role_id: roleId,
        };
        //console.log(moduluData);
        var str =
            ' <table class="layui-table" lay-even="" lay-skin="nob">' +
            '     <tbody>' +
            '     <tr>' +
            '     <td>用户名</td>' +
            '     <td>' +
            '     <input type="text" name="user_name" id="pop_user_name" lay-verify="title" value=' + moduluData.user_name + ' autocomplete="off" placeholder="请输入用户名" class="layui-input">' +
            '     </td>' +
            '     </tr>' +
            '     <tr>' +
            '     <td>姓名</td>' +
            '     <td>' +
            '     <input type="text" name="true_name" id="pop_true_name" lay-verify="title" value=' + moduluData.true_name + ' autocomplete="off" placeholder="请输入姓名" class="layui-input">' +
            '     </td>' +
            '     </tr>' +
            '     <tr>' +
            '     <td>手机号码</td>' +
            '     <td>' +
            '     <input type="text" name="collphone" id="pop_collphone" lay-verify="title" value=' + moduluData.collphone + ' autocomplete="off" placeholder="请输入手机号码" class="layui-input">' +
            '     </td>' +
            '     </tr>' +
            //'     <tr>' +
            //'     <td>身份</td>' +
            //'     <td>' +
            //'     <div class="layui-inline person-select"></div>' +
            //'     </td>' +
            //'     </tr>' +
            '     <tr>' +
            '     <td></td>' +
            '     <td>' +
            '      <button class="layui-btn enter-save-btn">确定修改</button>' +
            '        <button class="layui-btn layui-btn-primary layui-layer-close">取消</button>' +
            '     </td>' +
            '     </tr>' +
            '     </tbody>' +
            '     </table>';


        var layOpen = layer.open({
            type: 1,
            title: "修改信息",
            area: ['420px', '400px'], //宽高
            content: str
        });

        /*复制节点 并渲染*/
        personType.clone().appendTo(".person-select");
        $('.person-select #person-type option[value=' + roleId + ']').attr("selected", true);

        /*确认按键重新收集数据*/
        $(".enter-save-btn").on("click", function () {

            moduluData = {
                user_id: dataId,
                user_name: $("#pop_user_name").val(),
                true_name: $("#pop_true_name").val(),
                collphone: $("#pop_collphone").val(),
                role_id: $('.person-select #person-type option:selected').val(),
            };

            $.ajax({
                url: url + "index.php/Home/User/post_user",
                type: "post",
                dataType: "json",
                data: moduluData,
                success: function (res) {
                    if (res.result === 1) {

                        // 不刷新重新赋值
                        tr.find("td:eq(1)").text(moduluData.user_name)
                        tr.find("td:eq(2)").text(moduluData.true_name)
                        tr.find("td:eq(4)").text(moduluData.collphone)
                        tr.find("td:eq(3)").text($('.person-select #person-type option:selected').text())
                        $(".edit").attr("role-id", $('.person-select #person-type option:selected').val());

                        layer.close(layOpen);
                    }

                    var status = res.result;
                    status = (status == 1) ? 1 : 2;
                    layer.msg(res.mesg, {
                        icon: status,
                        skin: 'layer-ext-moon',//该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                        time: 1500
                    })
                }
            });

        });

    });

    /*修改密码*/
    $(".edit-pw").click(function () {
        var user_id = $(this).parent("td").attr("data-id");
        console.log(user_id);
        layer.prompt({title: '修改密码', formType: 3}, function (text, index) {
            $.ajax({
                url: url + "index.php/Home/User/updatePassword",
                type: "post",
                dataType: "json",
                data: {user_id: user_id, password: text},
                success: function (res) {
                    var status = res.result;
                    status = (status == 1) ? 1 : 2;
                    if (status == 1) {
                        layer.close(index);
                    }
                    layer.msg(res.mesg, {icon: status, time: 1500});
                }
            });
        });
    })
});
