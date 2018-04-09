<?php
return array(
//    'DB_TYPE'   => 'mysql', // 数据库类型
    // 'DB_HOST'   => 'xdm371251510.my3w.com', // 服务器地址120.26.76.181
    // 'DB_NAME'   => 'xdm371251510_db', // 数据库名test_waibao30
    // 'DB_USER'   => 'xdm371251510', // 用户名
    // 'DB_PWD'    => 'zf123456', // 密码
//    'DB_HOST'   => '127.0.0.1', // 服务器地址120.26.76.181
//    'DB_NAME'   => 'xyu', // 数据库名test_waibao30
//    'DB_USER'   => 'root', // 用户名
//    'DB_PWD'    => 'root', // 密码

//    'DB_TYPE'   => 'sqlsrv', // 数据库类型
//    'DB_HOST'   => 'i65h9o2kqv.database.chinacloudapi.cn', // 服务器地址120.26.76.181
//    'DB_NAME'   => 'LAUNDRY_SERVICE_HUIZHOU_ONECLEAR', // 数据库名test_waibao30
//    'DB_USER'   => 'LauSysDev', // 用户名
//    'DB_PWD'    => '!yoVg0N^dHOQtKoZ', // 密码
//    'DB_PORT'   => 1433, // 端口

    'DB_TYPE'   => 'sqlsrv', // 数据库类型
    'DB_HOST'   => '127.0.0.1', // 服务器地址120.26.76.181
    'DB_NAME'   => 'xyu', // 数据库名test_waibao30
    'DB_USER'   => 'test', // 用户名
    'DB_PWD'    => 'test', // 密码
    'DB_PORT'   => 1433, // 端口

//    'DB_TYPE'               =>  'pdo',            // 数据库类型
//    'DB_NAME'               =>  'LAUNDRY_SERVICE_TEST',           // 数据库名
//    'DB_USER'               =>  'LauSysDev',            // 用户名
//    'DB_PWD'                =>  '!yoVg0N^dHOQtKoZ',              // 密码
//
//    'DB_DSN'=> 'sqlsrv:Server=i65h9o2kqv.database.chinacloudapi.cn;database=LAUNDRY_SERVICE_TEST',

//    'DB_PORT'   => 3306, // 端口
    'DB_PREFIX' => '', //'配置项'=>'配置值'
    'DB_PARAMS'    =>    array(\PDO::ATTR_CASE => \PDO::CASE_NATURAL),

    'LOG_RECORD'=>  true, // 默认不记录日志
    'LOG_TYPE'  =>  'File', // 日志记录类型 默认为文件方式
    // 'DEFAULT_FILTER' => 'htmlspecialchars',//html特殊字符过滤
    'DEFAULT_FILTER' => false,
    'LOG_LEVEL' =>  'EMERG,ALERT,CRIT,ERR',// 允许记录的日志级别
    'LOG_EXCEPTION_RECORD' =>  true,    // 是否记录异常信息日志

    //中英文切换语言包配置项
    'LANG_SWITCH_ON'     => true,    //开启语言包功能
    'LANG_AUTO_DETECT'  => false, // 自动侦测语言
    'DEFAULT_LANG'      => 'zh-cn', // 默认语言
    'LANG_LIST'         => 'zh-cn,zh-hk,en-us', //必须写可允许的语言列表
    'VAR_LANGUAGE'     => '1', // 默认语言切换变量

    //密码加密盐值
    'CRYPT' => 'laundry',

    /* logo上传相关配置 */
    'LOGO_UPLOAD' => array(
        'mimes'    => '', //允许上传的文件MiMe类型
        'maxSize'  => 5*1024*1024, //上传的文件大小限制 (0-不做限制)
        'exts'     => 'jpg,gif,png,jpeg', //允许上传的文件后缀
        'autoSub'  => true, //自动子目录保存文件
        'subName'  => '',//array('date', 'Y-m-d'), //子目录创建方式，[0]-函数名，[1]-参数，多个参数使用数组
        'rootPath' => 'Public/Uploads/Logo/', //保存根路径
        'savePath' => '', //保存路径
        'saveName' => array('uniqid', ''), //上传文件命名规则，[0]-函数名，[1]-参数，多个参数使用数组
        'saveExt'  => '', //文件保存后缀，空则使用原后缀
        'replace'  => false, //存在同名是否覆盖
        'hash'     => true, //是否生成hash编码
        'callback' => false, //检测文件是否存在回调函数，如果存在返回文件信息数组
    ), //下载模型上传配置（文件上传类配置）
    /*仓库表属性值*/
    'WASHINGPLANTS' => 'WASHINGPLANTS',         //洗涤厂
    'CUSTOMER' => 'CUSTOMER',                    //客户
    'STOREHOUSE' => 'STOREHOUSE',               //仓库
    /*用户特殊角色属性名*/
	'ADMINMARK' => 'ADMIN',                       //管理员标记
    'FINANCEROLE' => 'FINANCEROLE',             //财务人员
    'LINENROLE' => 'LINENROLE',                 //收发管理员
	/*收发管理员权限*/
    'DISPATCHERAUTH' => array(
        array('name'=> '库存盘存', 'controller'=>'Inventory', 'action'=> 'choose'),
        array('name'=> '脏布草洗涤单', 'controller'=>'Order', 'action'=> 'choose_dirty'),
        array('name'=> '净布草使用单', 'controller'=>'Order', 'action'=> 'choose_clean')
    ),
);