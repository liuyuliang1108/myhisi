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
/**
 * 插件后台菜单
 * 字段说明
 * url【链接地址】如果不是外链请设置为默认：system/plugins/run
 * param【扩展参数】格式：_p=example&_c=控制器&_a=方法&aaa=自定义参数
 * 当url值为：system/plugins/run时，param必须录入，且_p=example是必传参数
 */
return [
  [
    'title' => '示例插件',
    'icon' => 'aicon ai-shezhi',
    'module' => 'admin',
    'url' => 'system/plugins/run',
    'param' => '_a=index&_c=index&_p=example',
    'target' => '_self',
    'debug' => 0,
    'system' => 0,
    'nav' => 1,
    'sort' => 0,
    'childs' => [
      [
        'title' => '分类列表',
        'icon' => 'aicon ai-shezhi',
        'module' => 'admin',
        'url' => 'system/plugins/run',
        'param' => '_a=index&_c=category&_p=example',
        'target' => '_self',
        'debug' => 0,
        'system' => 0,
        'nav' => 1,
        'sort' => 0,
        'childs' => [
          [
            'title' => '添加分类',
            'icon' => 'aicon ai-shezhi',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=add&_c=category&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '修改分类',
            'icon' => 'aicon ai-shezhi',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=edit&_c=category&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '删除分类',
            'icon' => 'aicon ai-shezhi',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=del&_c=category&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '状态设置',
            'icon' => '',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=status&_c=category&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
        ],
      ],
      [
        'title' => '文章列表',
        'icon' => 'aicon ai-shezhi',
        'module' => 'admin',
        'url' => 'system/plugins/run',
        'param' => '_a=index&_c=article&_p=example',
        'target' => '_self',
        'debug' => 0,
        'system' => 0,
        'nav' => 1,
        'sort' => 0,
        'childs' => [
          [
            'title' => '添加文章',
            'icon' => '',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=add&_c=article&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '修改文章',
            'icon' => '',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=edit&_c=article&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '删除文章',
            'icon' => '',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=del&_c=article&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '状态设置',
            'icon' => '',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=status&_c=article&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
          [
            'title' => '文章排序',
            'icon' => '',
            'module' => 'admin',
            'url' => 'system/plugins/run',
            'param' => '_a=sort&_c=article&_p=example',
            'target' => '_self',
            'debug' => 0,
            'system' => 0,
            'nav' => 0,
            'sort' => 0,
          ],
        ],
      ],
    ],
  ],
];