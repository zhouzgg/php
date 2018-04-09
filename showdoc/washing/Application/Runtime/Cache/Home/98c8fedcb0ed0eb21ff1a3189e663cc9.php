<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html lang="zh-cn">
<head>
    <meta charset="UTF-8">
<meta name="author" content="ifuner，18658226071@163.com"/>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<title><?php echo ($title); ?></title>
<link rel="shortcut icon" href="/Public/static/css/images/favicon.ico"/>
<!--每个页面都必须引用的css-->
<link rel="stylesheet" href="/Public/static/lib/layui/css/layui.css" media="all">
<link rel="stylesheet" href="/Public/static/lib/layer/skin/default/layer.css" media="all">

    <!--私有样式-->
    <link rel="stylesheet" href="/Public/static/css/style.css">
    <style>
        .ifuner_pop .layui-pop .layui-table input[readonly] {
            border-bottom: none;
        }
    </style>
</head>
<body>
<div class="layui-layout layui-layout-admin">

    <!--头部-->
    <div class="layui-header">
    <div class="layui-logo">E3 Smart Linen System</div>

    <!--<ul class="layui-nav layui-layout-left">-->
    <!--<li class="layui-nav-item"><a href="">控制台</a></li>-->
    <!--<li class="layui-nav-item"><a href="">商品管理</a></li>-->
    <!--<li class="layui-nav-item"><a href="">用户</a></li>-->
    <!--<li class="layui-nav-item">-->
    <!--<a href="javascript:;">其它系统</a>-->
    <!--<dl class="layui-nav-child">-->
    <!--<dd><a href="">邮件管理</a></dd>-->
    <!--<dd><a href="">消息管理</a></dd>-->
    <!--<dd><a href="">授权管理</a></dd>-->
    <!--</dl>-->
    <!--</li>-->
    <!--</ul>-->
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item">
            <a href="javascript:;">
                <?php echo (session('USER_NAME')); ?>
            </a>
            <dl class="layui-nav-child">
                <dd><a href=""><?php echo (L("_SECURITY_SETTINGS_")); ?></a></dd>
                <dd><a href=""><?php echo (L("_BASIC_INFORMATION_")); ?></a></dd>
                <dd><a class='login-out' href="javascript:"><?php echo (L("_SIGN_OUT_")); ?></a></dd>
            </dl>
        </li>

        <li class="layui-nav-item clear-cache"><a href="javascript:"><?php echo (L("_CLEAR_CACHE_")); ?></a></li>
    </ul>
</div>

    <!--左侧边栏-->
    <div class="layui-side layui-bg-black left-bar">
    <div class="layui-side-scroll">
        <?php if($index["id"] != ''): ?><div class="layui-nav-item">
                <ul class="layui-nav layui-nav-tree" lay-filter="test">
                    <li class="layui-nav-item <?php echo ($indexNode); ?>">
                        <a href="<?php echo ($index["url"]); ?>"><i class="layui-icon">&#xe68e;</i><span><?php echo ($index["name"]); ?></span></a>
                    </li>
                </ul>
            </div><?php endif; ?>
        <div class="layui-collapse" lay-accordion="">
            <?php
 foreach($result as $res) { $nodeSelect = ""; if($res['id'] == $node){ $nodeSelect = "layui-show"; } ?>
                <div class="layui-colla-item">
                    <h2 class="layui-colla-title"><?php echo $res['name'];?></h2>
                    <div class="layui-colla-content <?php echo $nodeSelect;?>">
                        <ul class="layui-nav layui-nav-tree" lay-filter="test">
                        <?php
 foreach($res['children'] as $child) { ?>
                                <?php
 if(strlen($child['url']) > 2){ $speSelect = ""; if($speNode == $child['id']){ $speSelect = "layui-this"; } ?>

                                <li class="layui-nav-item <?php echo $speSelect;?>">
                                    <a href="<?php echo $child['url'];?>"><?php echo $child['name'];?></a>
                                </li>

                                <?php
 } else { $lNodeSelect = ""; if($child['id'] == $lNode){ $lNodeSelect = "layui-nav-itemed"; } ?>

                                <li class="layui-nav-item <?php echo $lNodeSelect;?>">
                                    <a href="javascript:;"><?php echo $child['name'];?></a>
                                    <dl class="layui-nav-child">
                                        <?php
 foreach($child['children'] as $tchild) { $childSelect = ""; if($tchild['id'] == $childNode){ $childSelect = "layui-this"; } ?>
                                        <dd class="<?php echo $childSelect;?>"><a href="<?php echo $tchild['url'];?>"><?php echo $tchild['name'];?></a></dd>
                                        <?php
 } ?>
                                    </dl>
                                </li>

                                <?php } ?>
                        <?php
 } ?>
                        </ul>
                    </div>
                </div>
            <?php
 } ?>
            <!--<div class="layui-colla-item">
                <h2 class="layui-colla-title">客户设定</h2>
                <div class="layui-colla-content layui-show">
                    <ul class="layui-nav layui-nav-tree" lay-filter="test">
                        <li class="layui-nav-item">
                            <a href="javascript:;">洗涤厂管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="<?php echo U('WashingPlants/add_washing_plant');;?>">新增洗涤厂</a></dd>
                                <dd><a href="<?php echo U('WashingPlants/washing_plant_list');;?>">洗涤厂列表</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item">
                            <a href="javascript:;">客户管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Custom/add_custom">新增客户</a></dd>
                                <dd><a href="/index.php/Home/Custom/custom_list">客户列表</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item">
                            <a href="javascript:;">部门管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Department/add_dt">新增部门</a></dd>
                                <dd><a href="/index.php/Home/Department/dt_list">部门列表</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item <?php echo ($active); ?>">
                            <a class="" href="javascript:">仓库管理</a>
                            <dl class="layui-nav-child">
                                &lt;!&ndash;条件判断加类名&ndash;&gt;
                                <?php if($active2 == '1'): ?><dd class="layui-this">
                                        <a href="/index.php/Home/StoreHouse/add_storehouse">新增仓库</a>
                                    </dd>
                                    <?php else: ?>
                                    <dd>
                                        <a href="/index.php/Home/StoreHouse/add_storehouse">新增仓库</a>
                                    </dd><?php endif; ?>
                                <?php if($active2 == '2'): ?><dd class="layui-this">
                                        <a href="/index.php/Home/StoreHouse/storehouse_list">仓库列表
                                        </a>
                                    </dd>
                                    <?php else: ?>
                                    <dd>
                                        <a href="/index.php/Home/StoreHouse/storehouse_list">仓库列表
                                        </a>
                                    </dd><?php endif; ?>
                            </dl>
                        </li>
                        <li class="layui-nav-item"><a href="/index.php/Home/Inventory/choose">库存盘存</a></li>
                    </ul>

                </div>
            </div>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">单据管理</h2>
                <div class="layui-colla-content">
                    <ul class="layui-nav layui-nav-tree" lay-filter="test">
                        <li class="layui-nav-item"><a href="/index.php/Home/NewProduct/new_product">新品入库</a></li>

                        <li class="layui-nav-item">
                            <a href="javascript:;">订单管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Order/choose?type=dirty">脏布草洗涤单</a></dd>
                                <dd><a href="/index.php/Home/Order/choose?type=clean">净布草使用单</a></dd>
                            </dl>
                        </li>

                        <li class="layui-nav-item">
                            <a href="javascript:;">丢损管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/LossManage/break_list">报损列表</a></dd>
                                <dd><a href="/index.php/Home/LossManage/lose_list">丢失列表</a></dd>
                                <dd><a href="/index.php/Home/LossManage/break_verify">报损审核</a></dd>
                                <dd><a href="/index.php/Home/LossManage/lose_verify">丢失审核</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">报表管理</h2>
                <div class="layui-colla-content">
                    <ul class="layui-nav layui-nav-tree" lay-filter="test">
                        <li class="layui-nav-item"><a href="/index.php/Home/Excel/excel">报表管理</a></li>
                    </ul>
                </div>
            </div>
            <div class="layui-colla-item">
                <h2 class="layui-colla-title">高级管理</h2>
                <div class="layui-colla-content">
                    <ul class="layui-nav layui-nav-tree" lay-filter="test">
                        <li class="layui-nav-item">
                            <a href="javascript:">分类管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Classify/add_classify">新建分类</a></dd>
                                <dd><a href="/index.php/Home/Classify/classify">分类列表</a></dd>
                            </dl>
                        </li>

                        <li class="layui-nav-item">
                            <a href="javascript:;">人员管理</a>

                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/User/user_choose">新增用户</a></dd>
                                <dd><a href="/index.php/Home/User/user_part">角色管理</a></dd>
                                <dd><a href="/index.php/Home/User/user_auth">权限管理</a></dd>
                                <dd><a href="/index.php/Home/User/user_list">用户管理</a></dd>
                            </dl>
                        </li>

                        <li class="layui-nav-item">
                            <a href="javascript:;">设备管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Device/add_device">新建设备</a></dd>
                                <dd><a href="/index.php/Home/Device/device_list">设备列表</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item">
                            <a href="javascript:;">供应商管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Supplier/add_supplier">新增供应商</a></dd>
                                <dd><a href="/index.php/Home/Supplier/supplier_list">供应商列表</a></dd>
                            </dl>
                        </li>
                        <li class="layui-nav-item">
                            <a href="javascript:;">车辆管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Car/add_car">新建车辆</a></dd>
                                <dd><a href="/index.php/Home/Car/car_list">车辆列表</a></dd>
                            </dl>
                        </li>

                        <li class="layui-nav-item">
                            <a href="javascript:;">司机管理</a>
                            <dl class="layui-nav-child">
                                <dd><a href="/index.php/Home/Driver/add_driver">新增司机</a></dd>
                                <dd><a href="/index.php/Home/Driver/driver_list">司机列表</a></dd>
                            </dl>
                        </li>
                    </ul>
                </div>
            </div>-->
        </div>
    </div>
</div>

    <!--主体-->
    <div class="layui-body">
        <!-- 内容主体区域 -->
        <div style="padding: 15px;">
            <blockquote class="layui-elem-quote">
                <?php echo ($title); ?>
                <a target=_top href="/index.php/Home/StoreHouse/export_storehouse_list" class="layui-btn output-data"><?php echo (L("_EXPORT_EXCEL_TABLE_")); ?></a>
            </blockquote>
            <!--每个页面为了保持样式不冲突必须 唯一的样式必须统一一个不一样的类名-->
            <div class="layui-body-container store-house">
                <table class="layui-table">
                    <colgroup>
                        <col width="100">
                        <col width="100">
                        <col width="200">
                        <col>
                    </colgroup>
                    <thead>
                    <tr>
                        <th><?php echo (L("_STOREHOUSE_CUSTOMER_")); ?></th>
                        <th><?php echo (L("_STOREHOUSE_DEPARTMENT_")); ?></th>
                        <th><?php echo (L("_STOREHOUSE_NO_")); ?></th>
                        <th><?php echo (L("_STOREHOUSE_NAME_")); ?></th>
                        <th><?php echo (L("_STOREHOUSE_ADDRESS_")); ?></th>
                        <th><?php echo (L("_STOREHOUSE_PERSON_")); ?></th>
                        <th><?php echo (L("_STOREHOUSE_PHONE_")); ?></th>
                        <th><?php echo (L("_OPERATING_")); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php if(!empty($list)): if(is_array($list)): foreach($list as $key=>$var): ?><tr>
                                <td><?php echo ($var["CUSTOMER_NAME"]); ?></td>
                                <td><?php echo ($var["DEPARTMENT_NAME"]); ?></td>
                                <td><?php echo ($var["WAREHOUSE_ID"]); ?></td>
                                <td><?php echo ($var["WAREHOUSE_NAME"]); ?></td>
                                <td><?php echo ($var["WAREHOUSE_ADDRESS"]); ?></td>
                                <td><?php echo ($var["WAREHOUSE_CONTACT_PERSON"]); ?></td>
                                <td><?php echo ($var["WAREHOUSE_CONTACT_PHONE"]); ?></td>
                                <td>
                                    <a href="javascript:;" data-id="<?php echo ($var["WAREHOUSE_ID"]); ?>" data-dpid="<?php echo ($var["DEPARTMENT_ID"]); ?>" class="layui-btn layui-btn-normal layui-btn-radius edit-btn">
                                        修改资料
                                    </a>
                                    <!--<a href="<?php echo U('StoreHouse/storehouse_list_details');?>?lid=<?php echo ($var["WAREHOUSE_ID"]); ?>"-->
                                    <!--class="layui-btn layui-btn-primary layui-btn-radius">-->
                                    <!--<?php echo (L("_VIEW_DETAIL_")); ?>-->
                                    <!--</a>-->
                                </td>
                            </tr><?php endforeach; endif; ?>
                        <?php else: ?>
                        <tr>
                            <td colspan="8"><p class="layui-word-aux">
                                <span class="layui-icon"> &#xe69c;</span>
                                <?php echo (L("_LIST_NOT_DATA_")); ?>
                            </p></td>
                        </tr><?php endif; ?>
                    </tbody>
                </table>


                <script>
                    var tempObj = {
                        choose_dt:<?php echo json_encode($departmentList);?>,
                        th: {
                            CUSTOMER: "<?php echo (L("_STOREHOUSE_CUSTOMER_")); ?>",
                            DEPARTMENT: "<?php echo (L("_STOREHOUSE_DEPARTMENT_")); ?>",
                            STOREHOUSE_NO: "<?php echo (L("_STOREHOUSE_NO_")); ?>",
                            STOREHOUSE_NAME: "<?php echo (L("_STOREHOUSE_NAME_")); ?>",
                            ADDRESS: "<?php echo (L("_STOREHOUSE_ADDRESS_")); ?>",
                            PERSON: "<?php echo (L("_STOREHOUSE_PERSON_")); ?>",
                            PHONE: "<?php echo (L("_STOREHOUSE_PHONE_")); ?>",
                            OPERATING: "<?php echo (L("_OPERATING_")); ?>",
                        }
                    }
                </script>
            </div>
        </div>

        <!--底部-->
        <div class="layui-footer">
    <!-- 底部固定区域 -->
    <?php echo (L("_TITLE_")); ?>
</div>
    </div>
</div>
</body>
<!--每个页面都必须引入-->

<script src="/Public/static/lib/layui/layui.js"></script>
<script src="/Public/static/js/layui_config.js"></script>

<!--按每个页面引入-->
<script type="text/html" id="layui-pop">
    {{if status==1}}
    <div class="ifuner_pop">
        <div class="layui-layer-shade" id="layui-layer-shade1" times="1"
             style="z-index: 19891014; background-color: rgb(0, 0, 0); opacity: 0.3;"></div>

        <div class="layui-pop layer-anim"
             style="z-index: 19891015;">
            <div class="layui-layer-title">修改资料</div>
            <div id="" class="layui-layer-content" style="height: 198px;">
                <div style="padding: 20px;">
                    <table class="layui-table">
                        <tbody>
                        <tr>
                            <td>{{th.CUSTOMER}}</td>
                            <td><input type="text" id="custom" readonly value={{tr.CUSTOMER}}></td>
                        </tr>
                        <tr>
                            <td>{{th.DEPARTMENT}}</td>
                            <td>
                                <select name="modules" id="depart" lay-verify="required" lay-search="">
                                    <option value=""></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td>{{th.STOREHOUSE_NAME}}</td>
                            <td><input type="text" id="title" value="{{tr.STOREHOUSE_NAME}}"></td>
                        </tr>
                        <tr>
                            <td>{{th.ADDRESS}}</td>
                            <td><input type="text" id="address" value="{{tr.ADDRESS}}"></td>
                        </tr>
                        <tr>
                            <td>{{th.PERSON}}</td>
                            <td><input type="text" id="username" value="{{tr.PERSON}}"></td>
                        </tr>
                        <tr>
                            <td>{{th.PHONE}}</td>
                            <td><input type="text" maxlength="11" id="phone" value="{{tr.PHONE}}"></td>
                        </tr>
                        <tr>
                            <td>{{th.OPERATING}}</td>
                            <td>
                                <a href="javascript:;" class="layui-btn enter-edit">确认修改</a>
                                <a href="javascript:;" class="layui-btn layui-btn-primary layui-layer-close">取消返回</a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <span class="layui-layer-setwin">
            <a class="layui-layer-ico layui-layer-close layui-layer-close1"
               href="javascript:;">
            </a>
        </span>
        </div>
    </div>
    {{/if}}
</script>

<!--按每个页面引入-->
<script src="/Public/static/lib/template.js"></script>
<script src="/Public/static/js/StoreHouse/store_house_list.js?data=17121814"></script>
</html>