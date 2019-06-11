<?php
// +----------------------------------------------------------------------
// | HisiPHP框架[基于ThinkPHP5.1开发]
// +----------------------------------------------------------------------
// | Copyright (c) 2016-2021 http://www.hisiphp.com
// +----------------------------------------------------------------------
// | HisiPHP承诺基础框架永久免费开源，您可用于学习和商用，但必须保留软件版权信息。
// +----------------------------------------------------------------------
// | Author: 橘子俊 <364666827@qq.com>，开发者QQ群：50304283
// +----------------------------------------------------------------------

// 为方便系统升级，二次开发中用到的公共函数请写在此文件，禁止修改common.php文件
// ===== 系统升级时此文件永远不会被覆盖 =====
use think\exception\HttpResponseException;
use app\mycms\model\FieldType;
use \app\mycms\model\ModelField;
/**
 * json 数据输出
 * @param $data          data数据
 * @param int $code      code
 * @param string $msg    提示信息
 * @param array $param   额外参数
 * @param $httpCode      http状态码
 */
function show($data, $code = 1, $msg = '', $param = [], $httpCode = 200)
{
    $json = [
        'code' => $code,
        'msg' => $msg,
        'data' => $data,
    ];
    if ($param) {
        $json = array_merge($json, $param);
    }
    $response = json($json, $httpCode);
    throw new HttpResponseException($response);
}
/**
 * 获取数据库字段注释
 *
 * @param string $table_name 数据表名称(必须，不含前缀)
 * @param string $field 字段名称(默认获取全部字段,单个字段请输入字段名称)
 * @param string $table_schema 数据库名称(可选)
 * @return string
 */
/**
 * @name get_db_column_comment
 * @decs 获取数据库字段注释
 * 关联model：
 * @param string $table_name 数据表名称(必须，不含前缀)
 * @param bool $field 字段名称(默认获取全部字段,单个字段请输入字段名称)
 * @param string $table_schema 数据库名称(可选)
 * @param int $flag 默认为0，为1时除返回json数组外，增加返回注释
 * @return mixed|string
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/5/10 23:01
 */
function get_db_column_comment($table_name = '', $field = true, $table_schema = '',$flag=0){
    // 接收参数
    $database = config('database');
    $table_schema = empty($table_schema) ? $database['database'] : $table_schema;
    $table_name = $database['prefix'] . $table_name;

    // 缓存名称
    $fieldName = $field === true ? 'allField' : $field;
    $cacheKeyName = 'db_' . $table_schema . '_' . $table_name . '_' . $fieldName;

    // 处理参数
    $param = [
        $table_name,
        $table_schema
    ];

    // 字段
    $columeName = '';
    if($field !== true){
        $param[] = $field;
        $columeName = "AND COLUMN_NAME = ?";
    }

    // 查询结果
    $result = Db :: query("SELECT COLUMN_NAME as field,column_comment as comment FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = ? AND table_schema = ? $columeName", $param);
    // pp(Db :: getlastsql());
    if(empty($result) && $field !== true){
        return $table_name . '表' . $field . '字段不存在';
    }

    // 处理结果
    foreach($result as $k => $v){
        if(strpos($v['comment'], '#*#') !== false){//有#*#标识则
            $tmpArr = explode('#*#', $v['comment']); //以标识符分割成数组
            $data[$v['field']] = json_decode(end($tmpArr), true);//将数组最后一个键值由json格式转化为数组
            if ($flag) {
                $data[$v['field']][]=array_shift($tmpArr);
            }
        }else{//无#*#标识则
            $data[$v['field']]=$v['comment'];
        }
    }

    return $data[$v['field']];
}

function field_option($id=54,$str = '')
{
    $list = FieldType::all();
    $tid=ModelField::where('id',$id)->value('tid');
    foreach ($list as $v) {

        if ($tid == $v['id']) {
            $str .= '<option level="1" value="'.$v['id'].'" selected>['.$v['name'].']'.$v['title'].'</option>';
        } else {
            $str .= '<option level="1" value="'.$v['id'].'">['.$v['name'].']'.$v['title'].'</option>';
        }
    }
    return $str;
}