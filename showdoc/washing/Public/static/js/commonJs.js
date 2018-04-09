/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";
layui.define(['jquery'], function (exports) { //提示：模块也可以依赖其它模块，如：layui.define('layer','common', callback);
    var $ = layui.$
        , url = base.baseUrl;
    var obj = {
        header: function () {
            /*注销*/
            $(".login-out").click(function () {
                $.ajax({
                    type: "get",
                    url: url + "index.php/Home/Login/logOut",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        if (res.result === 1) {

                            layer.msg('加载中,正在跳转登录页', {
                                icon: 16
                                , shade: 0.01
                            });

                            setTimeout(function () {
                                window.location.replace('/index.php/Home/Login/login')
                            }, 1500);
                        } else {
                            layer.msg(res.mesg);
                        }
                    }
                })
            });

            /*清除缓存*/
            $(".clear-cache").click(function () {
                var callback, index;
                $.ajax({
                    type: "post",
                    url: url + "index.php/Home/System/clearCatch",
                    dataType: "json",
                    success: function (res) {
                        console.log(res);
                        var status = res.result;
                        if (status === 1) {
                            callback = function () {
                                window.location.reload();
                            }
                        } else {
                            callback = function () {
                                layer.close(index);
                            }
                        }
                        status = (status == 1 ? 1 : 2);

                        layer.alert(res.mesg, {
                            icon: status
                            , closeBtn: 0
                        }, function () {
                            callback();
                        })
                    }
                })
            })

        }
    };

    //输出test接口
    exports('common', obj);
});