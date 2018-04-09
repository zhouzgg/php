<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
        .layui-col-lg1 {
            width: auto;
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
            <blockquote class="layui-elem-quote"><?php echo ($title); ?></blockquote>
            <!--每个页面为了保持样式不冲突必须 唯一的样式必须统一一个不一样的类名-->
            <div class="layui-body-container inventory">

                <!--<fieldset class="layui-elem-field layui-field-title" style="margin-top: 30px;">
                    <legend>
                          <span class="layui-breadcrumb" lay-separator="—">
                            <a href="javascript:"><cite><?php echo (L("_ORDER_SELECT_")); ?>：</cite></a>
                            <a href="javascript:"><cite></cite></a>
                            <a href="javascript:"><cite></cite></a>
                            <a href="javascript:"><cite></cite></a>
                        </span>
                    </legend>
                </fieldset>-->

                <form action="" class="layui-form">
                    <ul class="layui-timeline" style="margin-top: 20px;">
                        <li class="layui-timeline-item">
                            <span class="layui-icon time-num active">1</span>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title"><?php echo (L("_ORDER_FIRST_STEP_")); ?></h3>
                                <div class="timeline-container">
                                    <div class="layui-row layui-col-space20">
                                        <?php if(is_array($customer_list)): foreach($customer_list as $key=>$val): ?><div class="layui-col-xs4 layui-col-sm3 layui-col-md2 layui-col-lg1">
                                                <input type="radio" name="choose_custom" title="<?php echo ($val["WAREHOUSE_NAME"]); ?>" value="<?php echo ($val["WAREHOUSE_ID"]); ?>">
                                            </div><?php endforeach; endif; ?>
                                    </div>
                                    <input type="hidden" id="customer_data" value=""/>
                                    <div class="layui-btn choose-custom" style='margin-top: 40px;'><?php echo (L("_ORDER_SURE_")); ?></div>
                                </div>
                            </div>
                        </li>
                        <li class="layui-timeline-item">
                            <span class="layui-icon time-num">2</span>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title"><?php echo (L("_ORDER_SECOND_STEP_")); ?></h3>
                                <input type="hidden" id="has-choose-dp" value="" readonly name="">
                                <div class="timeline-container hide">
                                    <ul id="ifuner_tree" class="clearfix"></ul>
                                    <div class="layui-btn choose-dp" style='margin-top: 40px;' id="choose-dp"><?php echo (L("_ORDER_SURE_")); ?></div>
                                </div>
                            </div>
                        </li>

                        <li class="layui-timeline-item">
                            <span class="layui-icon time-num">3</span>
                            <div class="layui-timeline-content layui-text">
                                <h3 class="layui-timeline-title"><?php echo (L("_ORDER_THIRD_STEP_DATE_")); ?></h3>
                                <!--隐藏的表单-->
                                <div class="timeline-container hide">
                                    <fieldset class="layui-elem-field" style="margin-top: 20px;">
                                        <legend><?php echo (L("_CUSTOMER_DETAIL_DATA_SELECT_")); ?></legend>
                                        <div class="layui-field-box layui-input-inline" >
                                            <input type="text" class="layui-input" id="date" placeholder=" - "><span style="color: red"><?php echo (L("_ORDER_LINEN_MAX_DAY_")); ?></span>
                                        </div>
                                    </fieldset>
                                    <div class="layui-btn choose-end" style='margin-top: 40px;'><?php echo (L("_ORDER_SURE_")); ?></div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </form>
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
<script src="/Public/static/lib/layui/lay/modules/layui-xtree.js?data=171219"></script>
<!--按每个页面引入-->
<script src="/Public/static/js/order/linen_choose.js?data=171215"></script>
</html>