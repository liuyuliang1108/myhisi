<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2018 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------

namespace plugins\example\home;
use app\common\controller\Common;
defined('IN_SYSTEM') or die('Access Denied');
use plugins\example\model\PluginsExampleNews as NewsModel;
use plugins\example\model\PluginsExampleCategory as CategoryModel;

/**
 * [示例插件]前台文章展示
 * @package plugins\example\admin
 */
class Index extends Common
{
    public function index()
    {
    	$id = get_num();
    	$data['news'] = NewsModel::get($id);
    	$data['category'] = CategoryModel::where('status', 1)->column('id,name,status');
    	$this->assign('data', $data);
        return $this->fetch();
    }
}