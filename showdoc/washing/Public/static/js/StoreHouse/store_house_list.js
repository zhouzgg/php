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

    var a2Module = {
        data: [
            {
                DEPARTMENT_ID: "",
                DEPARTMENT_NAME: ""
            }
        ],
        mesg: "",
        result: "",
    };

    $('.edit-btn').click(function () {
        console.log(tempObj);
        /*获取当前点击的节点信息*/

        var that = $(this)
            , tr = that.parent("td").parent("tr")
            , currentId = $(this).attr("data-dpid")
            , warehouse_id = $(this).attr("data-id");
        tempObj.tr = {
            CUSTOMER: tr.find("td:eq(0)").text(),
            DEPARTMENT: tr.find("td:eq(1)").text(),
            STOREHOUSE_NO: tr.find("td:eq(2)").text(),
            STOREHOUSE_NAME: tr.find("td:eq(3)").text(),
            ADDRESS: tr.find("td:eq(4)").text(),
            PERSON: tr.find("td:eq(5)").text(),
            PHONE: tr.find("td:eq(6)").text(),
        };
        tempObj.status = 1;
        //查找客戶下部門
        getdepartment(currentId);
        //console.log(tempObj);
        var temp = template("layui-pop", tempObj);
        $("body").append(temp);
        $('#depart option[value=' + currentId + ']').attr("selected", true);
        form.render();
        $(".layui-layer-close").click(function () {
            $(".ifuner_pop").remove();
        });


        /*点击确认修改*/
        $(".enter-edit").click(function () {
            var depart = $("#depart")
                , title = $("#title")
                , address = $("#address")
                , username = $("#username")
                , phone = $("#phone");

            if(depart.find("option:selected").val() == "") {
                layer.msg("请选择管理部门！");
                return false;
            }
            if (title.val() == "") {
                layer.msg("请填写名称！");
                return false;
            }
            if (address.val() == "") {
                layer.msg("请填写地址！");
                return false;
            }
            if (username.val() == "") {
                layer.msg("请填写名称！");
                return false;
            }
            if (phone.val() == "") {
                layer.msg("请填写手机号！");
                return false;
            }
            if (phone.val().length != 11) {
                layer.msg("手机格式错误！");
                return false;
            }

            var moduleData = {
                warehouse_id: warehouse_id,
                customer_id: "",
                department_id: depart.find("option:selected").val(),
                department_name: title.val(),
                department_address: address.val(),
                department_contact_person: username.val(),
                department_contact_phone: phone.val()
            };
            $.ajax({
                url: url + "index.php/Home/StoreHouse/post_storehouse",
                type: "post",
                dataType: "json",
                data: moduleData,
                success: function (res) {
                    console.log(res);
                    if (res.result === 1) {
                        /*重置*/
                        // $(".ifuner_pop").remove();
                        window.location.reload();
                    }else{
                        layer.alert(res.mesg, {
                            icon: 2,
                            skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                        })
                    }

                }
            });
        });

        //获取部门
        function getdepartment(currentId)
        {
            if(currentId != "")
            {
                var a2 = $.ajax({url: url + "index.php/Home/StoreHouse/department_name_list?customer_id="+currentId, dataType: "json"});
                /*渲染表单*/
                $.when(a2).done(function(v2) {
                    a2Module = v2;
                    if (a2Module.result === 1) {
                        var _htmls = "";
                        $.each(a2Module.data, function (i, v) {
                            _htmls += '<option value=' + v.DEPARTMENT_ID + '>' + v.DEPARTMENT_NAME + '</option>';
                        });
                        $("#depart").html(_htmls);
                    }
                });
            }
        }
    });

});
