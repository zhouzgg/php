/**
 * ifuner 制作 @18658226071@163.com
 */
"use strict";
layui.use(['jquery', 'layer', 'common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , url = base.baseUrl
        , element = layui.element
        , common = layui.common
        , xtree = null;

    common.header();
    var username = $("#username")
        , relName = $("#rel-name")
        , phone = $("#phone")
        , password = $("#password")
        , confirm_password = $("#confirm_password")
        , partName = $("#part-name")
        , custom = $("#custom")
        , chooseDp = $(".choose-dp");

    var moduleData = {
        user_id: "", /*新增不需要传，编辑需要传*/
        user_name: "",
        password: "",
        true_name: "",
        collphone: "",
        //customer: "",
        role_id: ""
    };
    var choosedp = {
        value: [],
        title: [],
    };

    var ifuner = new allFunc();
    $(".add-btn").click(function () {
        ifuner.add();
    });

    $(".reset-btn").click(function () {
        ifuner.reset();
    });

    //客户选择
    /*$(".custom-select dd").on("click", function () {
        moduleData.customer = $(this).attr("lay-value");
    });*/

    /*选择客户*/
    function setdepartment(customer_id)
    {
        if(customer_id != "")
        {
            var sendurl = url + "index.php/Home/User/getdepartmentlist?customer_id="+customer_id;
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
            form.render();
            /*var str = "";
             var time = +(new Date);
             $.ajax({
             url: url + "index.php/Home/User/getdepartmentlist?time=" + time,
             type: "post",
             dataType: "json",
             data: {customer_id: customer_id},
             success: function (res) {
             res.data = (res == undefined) ? [] : res;
             console.log(res);
             $.each(res.data, function (i, v) {
             str += '<input type="checkbox" value=' + v.value + ' title=' + v.title + '>';
             });
             $(".ifuner_pop .all-checkbox").html(str);

             }
             });*/
        }
    }

    /*选择部门*/
    chooseDp.click(function () {
        var customer_id = $(".custom-select").find("option:selected").val();
        if(customer_id == "")
        {
            layer.msg('请先选择客户！');
            return false;
        }
        setdepartment(customer_id);
        $(".ifuner_pop").show();
        $(".close-pop").click(function () {
            $(".ifuner_pop").hide();
        });
        $('.choose-btn').click(function () {
            // 选择之前制空
            choosedp = {
                value: [],
                title: [],
            };
            //点击获取选中的数据
            $.each($(".ifuner_pop input:checked"), function (i, v) {
                choosedp.value.push($(v).val());
                choosedp.title.push($(v).attr("title"));
            });
            // 赋值给页面的表单
            var tempValue = choosedp.title.join(",");
            $(".choose-dp .layui-input").val(tempValue);
            $(".ifuner_pop").hide();
        });
    });

    function allFunc() {
        // 实例方法
        this.add = function () {
            if (username.val() !== "") {

                if (relName.val() !== "") {
                    /*手机号*/
                    if (phone.val() !== undefined && phone.val().length == 11) {

                        /*密码*/
                        if (password.val().length > 5) {

                            /*确认密码*/
                            if (password.val() === confirm_password.val()) {

                                /*角色名称*/
                                var parNameVal = partName.find("option:selected").val();
                                if (parNameVal !== "") {
                                    /*请求数据*/

                                    /*普通用户*/
                                    if (base.getUrl().cid !== "admin") {

                                        /*再次验证客户和部门*/
                                        if (custom.find("option:selected").val() !== "") {

                                            if (choosedp.value !== "") {

                                                /*管理员账户*/
                                                moduleData = {
                                                    user_id: "",
                                                    user_name: username.val(),
                                                    password: password.val(),
                                                    true_name: relName.val(),
                                                    collphone: phone.val(),
                                                    role_id: parNameVal,
                                                    customer_id: custom.find("option:selected").val(),
                                                    department_id: choosedp.value.join(",")
                                                };
                                                addAjax(moduleData);
                                            } else {
                                                layer.tips('请选择部门', '.custom', {
                                                    tips: [1, '#FF5722'] //还可配置颜色
                                                });
                                            }

                                        } else {
                                            layer.tips('请选择客户', '.custom', {
                                                tips: [1, '#FF5722'] //还可配置颜色
                                            });
                                        }
                                    } else {
                                        /*管理员账户*/
                                        moduleData = {
                                            user_id: "",
                                            user_name: username.val(),
                                            password: password.val(),
                                            true_name: relName.val(),
                                            collphone: phone.val(),
                                            role_id: parNameVal
                                        };
                                        addAjax(moduleData)
                                    }

                                } else {
                                    layer.tips('请选择角色', '.layui-select-title', {
                                        tips: [1, '#FF5722'] //还可配置颜色
                                    });
                                }

                            } else {
                                layer.tips('两次输入的密码不一致', '#confirm_password', {
                                    tips: [1, '#FF5722'] //还可配置颜色
                                });
                            }

                        } else {
                            layer.tips('密码长度不对', '#password', {
                                tips: [1, '#FF5722'] //还可配置颜色
                            });
                        }

                    } else {
                        layer.tips('手机号格式不正确', '#phone', {
                            tips: [1, '#FF5722'] //还可配置颜色
                        });
                    }

                } else {
                    layer.tips('真实姓名不能为空', '#rel-name', {
                        tips: [1, '#FF5722'] //还可配置颜色
                    });
                }
            } else {
                layer.tips('用户名不能为空', '#username', {
                    tips: [1, '#FF5722'] //还可配置颜色
                });
            }
        },
            this.reset = function () {
                $(".layui-form input").val("");
            }
    }

    function addAjax(moduleData) {
        $.ajax({
            url: url + "index.php/Home/User/post_user",
            type: "post",
            dataType: "json",
            data: moduleData,
            success: function (res) {
                console.log(res);
                if (res.result === 1) {

                    /*重置*/
                    ifuner.reset();
                }
                var status = res.result;
                status = (status == 1) ? 1 : 2;
                layer.alert(res.mesg, {
                    icon: status,
                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                })
            }
        });
    }
});
