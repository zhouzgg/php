/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";

layui.use(['jquery', 'layer','common', 'form', 'element', 'upload'], function () {
    var layer = layui.layer
        , $ = layui.$
        , form = layui.form
        , upload = layui.upload
        , element = layui.element
        , common = layui.common;

     common.header();
    /*上传*/

    //设定文件大小限制
    upload.render({
        elem: '#test7'

        /*文康后台接口*/
        , url: 'http://zb.t.bjdyhz.com/API.php/Home/CommunityNews/news_upload_img'
        , size: 2048 //限制文件大小，单位 KB
        , done: function (res) {
            console.log(res);
        }

    });

});
