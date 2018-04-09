<?php
/**
 * 系统管理
 */
namespace Home\Controller;
use Think\Controller;

class SystemController extends CommonController {

    /*清除缓存*/
    public function clearCatch()
    {
        if(_removeTimeoutFile(CACHE_PATH))
        {
            backResult(1, L("_SYSTEM_CATCH_SUCCESS_"));
        }
        else
        {
            backResult(0, L("_SYSTEM_CATCH_ERROR_"));
        }
    }
}