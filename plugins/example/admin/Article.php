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

namespace plugins\example\admin;
use app\system\admin\Admin;
defined('IN_SYSTEM') or die('Access Denied');
use plugins\example\model\PluginsExampleNews as NewsModel;
use plugins\example\model\PluginsExampleCategory as CategoryModel;

/**
 * [示例插件]文章管理
 * @package plugins\example\admin
 */
class Article extends Admin
{
    // [通用添加、修改专用] 模型名
    protected $hisiModel = 'PluginsExampleNews';
    // [通用添加、修改专用] 验证器类
    protected $hisiValidate = 'PluginsExampleNews';

    protected function initialize()
    {
        parent::initialize();
        if ($this->request->action() != 'index' && !$this->request->isPost()) {
            $category = CategoryModel::getSelect(CategoryModel::getChilds());
            $this->assign('category', $category);
        }
    }

    public function index()
    {
        if ($this->request->isAjax()) {

            $where      = $data = [];
            $page       = $this->request->param('page/d', 1);
            $limit      = $this->request->param('limit/d', 15);
            $keyword    = $this->request->param('keyword/s');
            $cid        = $this->request->param('cid/d');

            if ($cid) {
                $where[] = ['cid', '=', $cid];
            }
            if ($keyword) {
                $where[] = ['title', 'like', '%'.$keyword.'%'];
            }

            $data['data']   = NewsModel::with('hasCategory')->where($where)->page($page)->limit($limit)->select();
            $data['count']  = NewsModel::where($where)->count('id');
            $data['code']   = 0;
            return json($data);

        }

        // 分组切换类型 0无需分组切换，1单个分组，2多个分组切换[无链接]，3多个分组切换[有链接]，具体请看application/system/view/layout.html
        $this->assign('hisiTabType', 0);
        // tab切换数据
        // $hisiTabData = [
        //     ['title' => '后台首页', 'url' => 'system/index/index'],
        // ];
        // current 可不传
        // $this->assign('hisiTabData', ['menu' => $hisiTabData, 'current' => 'system/index/index']);
        $this->assign('hisiTabData', '');
        return $this->fetch();
    }
}