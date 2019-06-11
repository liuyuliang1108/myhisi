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

namespace plugins\example\model;

use think\Model;
use think\Loader;

class PluginsExampleNews extends Model
{
    // 定义时间戳字段名
    protected $createTime = 'ctime';
    protected $updateTime = 'mtime';
    // 自动写入时间戳
    protected $autoWriteTimestamp = true;

    public function getContentAttr($value)
    {
        return htmlspecialchars_decode($value);
    }

    public function hasCategory()
    {
        return $this->hasOne('PluginsExampleCategory', 'id', 'cid');
    }

    /**
     * 入库
     * @param array $data 入库数据
     * @author 橘子俊 <364666827@qq.com>
     * @return bool
     */  
    public function storage($data = [])
    {
        if (empty($data)) {
            $data = request()->post();
        }

        // 验证
        $valid = Loader::validate('\plugins\example\validate\PluginsExampleNews');
        if($valid->check($data) !== true) {
            $this->error = $valid->getError();
            return false;
        }

        if (isset($data['id']) && !empty($data['id'])) {
            $res = $this->update($data);
        } else {
            $res = $this->create($data);
        }
        if (!$res) {
            $this->error = '保存失败';
            return false;
        }
        
        return $res;
    }
}