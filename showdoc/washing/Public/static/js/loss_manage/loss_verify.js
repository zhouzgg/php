/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , url = base.baseUrl
        , element = layui.element
        , common = layui.common;

     common.header();
    var moduleData = {
        status: "",
        main_id: base.getUrl().lid,
        explanation: ""
    }

    //审核通过
    $(".ok-btn").click(function () {
        console.log(1);
        moduleData.status = 3;
        delete moduleData.explanation;
        postAjax(moduleData);
    });

    //审核不通过
    var pop = "";
    $(".fail-btn").click(function () {
        layer.prompt({title: "请填写不通过的原因"}, function (val, index) {
            console.log(val);
            moduleData.status = 2;
            moduleData.explanation = val;

            postAjax(moduleData);
            layer.close(index);
        });
    });

    function postAjax(moduleData, callback) {
        /*发送请求*/
        $.ajax({
            url: url + "index.php/Home/LossManage/submit_verify",
            type: "post",
            dataType: "json",
            data: moduleData,
            success: function (res) {

                if (res.result === 1) {
                    /*重置*/
                }
                callback();
                layer.alert(res.mesg, {
                    icon: 1,
                    skin: 'layer-ext-moon' //该皮肤由layer.seaning.com友情扩展。关于皮肤的扩展规则，去这里查阅
                })
            }
        });
    }
});
