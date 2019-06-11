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
namespace plugins\example;
use app\common\controller\Plugins;
defined('IN_SYSTEM') or die('Access Denied');
use plugins\example\model\PluginsExampleNews as NewsModel;

/**
 * 示例插件插件
 * @package plugins\example
 */
class example extends Plugins
{
    /**
     * @var array 插件钩子清单
     * 插件安装后，新添加的钩子方法需要重装插件才会生效
     */
    public $hooks = [
        // 钩子名称 => 钩子说明【系统钩子，说明不用填写】
        'system_admin_tips',
        'example_hook' => '这是一个示例钩子',
    ];

    /**
     * system_admin_tips钩子方法
     * @param $params
     */
    public function systemAdminTips($params)
    {
        $rows = NewsModel::select();
        $this->assign('exampleNews', $rows);
        return $this->fetch('system_admin_tips');
    }

    /**
     * exampleHook钩子方法
     * @param $params
     */
    public function exampleHook($params)
    {
        echo '<div class="layui-collapse page-tips"><div class="layui-colla-item"><h2 class="layui-colla-title">温馨提示</h2><div class="layui-colla-content layui-show"><p>这是一个自定义的插件钩子(/plugins/example/example.php)</p> </div></div></div>';
    }

    /**
     * 安装前的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function install()
    {
        return true;
    }

    /**
     * 安装后的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function installAfter()
    {
        return true;
    }

    /**
     * 卸载前的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function uninstall()
    {
        return true;
    }

    /**
     * 卸载后的业务处理，可在此方法实现，默认返回true
     * @return bool
     */
    public function uninstallAfter()
    {
        return true;
    }

}