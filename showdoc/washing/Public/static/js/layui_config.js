/**
 * ifuner 制作 @18658226071@163.com
 */

"use strict";
/*统一请求接口*/

window.base = {
    // 实例方法
    baseUrl: "/",
    getUrl:function () {
        var url = window.location.search;
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            str = str.split("&");
            for (var i = 0; i < str.length; i++) {
                //theRequest[str[i].split("=")[0]] = unescape(str[i].split("=")[1]);
                theRequest[str[i].split("=")[0]] = unescape(str[i].split("=")[1]);
            }
        }
        return theRequest;
    }
}

setTimeout(function () {
    // console.log('%c @前端：ifuner', ' text-shadow: 0 1px 0 #ccc,0 2px 0 #c9c9c9,0 3px 0 #bbb,0 4px 0 #b9b9b9,0 5px 0 #aaa,0 6px 1px rgba(0,0,0,.1),0 0 5px rgba(0,0,0,.1),0 1px 3px rgba(0,0,0,.3),0 3px 5px rgba(0,0,0,.2),0 5px 10px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.2),0 20px 20px rgba(0,0,0,.15);font-size:3em')
    // console.log('%c @后端：天天想米', ' text-shadow: 0 1px 0 #ccc,0 2px 0 #c9c9c9,0 3px 0 #bbb,0 4px 0 #b9b9b9,0 5px 0 #aaa,0 6px 1px rgba(0,0,0,.1),0 0 5px rgba(0,0,0,.1),0 1px 3px rgba(0,0,0,.3),0 3px 5px rgba(0,0,0,.2),0 5px 10px rgba(0,0,0,.25),0 10px 10px rgba(0,0,0,.2),0 20px 20px rgba(0,0,0,.15);font-size:3em')
}, 3000);

layui.config({
    dir: '/Public/static/lib/layui/' //layui.js 所在路径（注意，如果是script单独引入layui.js，无需设定该参数。），一般情况下可以无视
    , version: true //一般用于更新模块缓存，默认不开启。设为true即让浏览器不缓存。也可以设为一个固定的值，如：201610
    , debug: true //用于开启调试模式，默认false，如果设为true，则JS模块的节点会保留在页面
    , base: '' //设定扩展的Layui模块的所在目录，一般用于外部模块扩展
})
.extend({ //设定模块别名
    common: '/Public/static/js/commonJs' //如果test.js是在根目录，也可以不用设定别名
});