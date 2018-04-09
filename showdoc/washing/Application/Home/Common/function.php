<?php
/**
 * 前台公共库文件
 * 主要定义前台公共函数库
 */


/**
 * 数据返回
 *
 * @param int $code 返回编码
 * @param string $mesg 返回说明
 * @param array $list 返回的列表
 */
function backResult($code = 1, $mesg = "", $list = array())
{
    $return['result'] = $code;
    $return['mesg'] = $mesg;
    if(!empty($list))
    {
        $return['data'] = $list;
    }
    echo json_encode($return, JSON_UNESCAPED_UNICODE);
    exit;
}

/**
 * 导出
 */
function explode_csv($filename,$data)
{
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

/**
 * 树形添加alias
 */
function tree_list_addalias($result)
{
    foreach($result as $key=>$res)
    {
        if(isset($res['children']))
        {
            return tree_list_addalias($res['children']);
        }
        else
        {
            $result[$key]['alias'] = 1;
        }
    }
    return $result;
}

/**
 * 树形添加alias
 */
function tree_list_addliasecond($result)
{
    foreach($result as $key=>$res)
    {
        if(isset($res['data']) && !empty($res['data']))
        {
            return tree_list_addliasecond($res['data']);
        }
        else
        {
            $result[$key]['data'] = [];
        }
    }
    return $result;
}

/**
 * 获取文件夹下面的所有文件
 * @param $dir
 * @return array|bool
 */
function getPathAllFiles($dir)
{
    if(!is_dir($dir))
    {
        return false;
    }
    $dirs[] = '';     # 用于记录目录

    $files = array(); # 用于记录文件

    while(list($k,$path)=each($dirs))
    {
        $absDirPath = "$dir/$path";     # 当前要遍历的目录的绝对路径

        $handle = opendir($absDirPath); # 打开目录句柄

        readdir($handle);               # 先调用两次 readdir() 过滤 . 和 ..

        readdir($handle);               # 避免在 while 循环中 if 判断

        while(false !== $item=readdir($handle))
        {
            $relPath = "$path/$item";   # 子项目相对路径

            $absPath = "$dir/$relPath"; # 子项目绝对路径

            # 如果是一个目录，则存入到数组 $dirs
            if(is_dir($absPath))
            {
                $dirs[] = $relPath;
            }
            # 否则是一个文件，则存入到数组 $files
            else
            {
                $files[] = $relPath;
            }
        }
        closedir($handle); # 关闭目录句柄
    }
    return $files;
}

/**
 * 删除指定目录下的文件
 */
function _removeTimeoutFile($dir)
{
    if(!is_dir($dir))
    {
        return false;
    }
    $path = $dir;
    $files = getPathAllFiles($dir);
    if(!empty($files))
    {
        foreach($files as $k=>$v)
        {
            $file = $path.$v;
            if(is_file($file))
            {
                @unlink($file);
            }
        }
    }
    return true;
}

/**
 * 获取子级
 */
function getChildrenIds($id = "")
{
    $resData = "";

    if(!empty($id))
    {
        $M = M();
        //自定义mysql函数
        $sql = "select queryChildrenDepart('".$id."');";

        $res = $M ->query($sql);

        $resData = array_values($res[0]);
    }
    return $resData;
}