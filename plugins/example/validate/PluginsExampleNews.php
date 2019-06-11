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

namespace plugins\example\validate;

use think\Validate;

/**
 * 新闻验证器
 * @package plugins\example\validate
 */
class PluginsExampleNews extends Validate
{
    //定义验证规则
    protected $rule = [
        'title|新闻标题' => 'require',
        'content|新闻内容' => 'require',
        'cid|新闻分类' => 'require|number',
    ];
}
