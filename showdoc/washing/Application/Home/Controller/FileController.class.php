<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;
use Think\Controller;
/**
 * 文件控制器
 * 主要用于下载模型的文件上传和下载
 */

class FileController extends CommonController {

	/* 文件上传 */
	public function uploadLogo()
	{
		$logo_file = $_FILES['file'];

		if($logo_file)
		{
			$uploade = new \Think\Upload(C("LOGO_UPLOAD"));

			$info = $uploade->upload();

			if($info)
			{
				$rootPath = C("LOGO_UPLOAD");														//配置

				$saveFile = '/'.$rootPath['rootPath'].$info['file']["savepath"].$info['file']["savename"];					//证书路径

				backResult(1, L('_UPLOADE_SUCCESS_'), array("logo_file"=>$saveFile));
			}
			else
			{
				backResult(0, L("_UPLOADE_ERROR_"), $info);
			}
		}
		else
		{
			backResult(0, L("_NOT_SELECT_FILE_"));
		}
	}
}
