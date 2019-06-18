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

/**
 * 数据签名认证
 * @param array $data 被认证的数据
 * @return string       签名
 */


if (!function_exists('sign')) {
    function sign($data)
    {
        // 数据类型检测
        if (!is_array($data)) {
            $data = (array)$data;
        }
        ksort($data); // 排序
        $code = http_build_query($data); // url编码并生成query字符串
        $sign = sha1($code); // 生成签名
        return $sign;
    }
}

/**打印输出
 * @param $arr
 */
if (!function_exists('p')) {
    function p($arr)
    {
        echo '<pre>' . print_r($arr, true) . '</pre>';
    }
}

/**
 * 将返回的数据集转换成树
 * @param array $list 数据集
 * @param string $pk 主键
 * @param string $pid 父节点名称
 * @param string $child 子节点名称
 * @param integer $root 根节点ID
 * @return array          转换后的树
 */
if (!function_exists('list_to_tree')) {
    function list_to_tree($list, $pk = 'id', $pid = 'pid', $child = 'child', $root = 0)
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }
}

/**
 * 获取当前登陆用户uid
 * @return mixed
 */
if (!function_exists('get_user_id')) {
    function get_user_id()
    {
        return session('user_auth.uid');
    }
}
function http_curl($url, $data = [], $header = [], $ispost = true)
{

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //判断是否加header
    if ($header) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    }
    //判断是否是POST请求
    if ($ispost) {
        // post数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // post的变量
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    $output = curl_exec($ch);
    curl_close($ch);
    //打印获得的数据
    return $output;
}

/**
 * @param string $msg 待提示的消息
 * @param string $url 跳转地址
 * @param int $time 弹出维持时间（单位秒）
 */
function alert_error($msg = '', $url = null, $time = 3)
{
    if (is_null($url)) {
        $url = 'parent.location.reload();';
    } else {
        $url = 'parent.location.href=\'' . $url . '\'';
    }
    if (request()->isAjax()) {
        $str = [
            'code' => 0,
            'msg' => $msg,
            'url' => $url,
            'wait' => $time,
        ];
        $response = think\Response::create($str, 'json');
    } else {
        $str = '<script type="text/javascript" src="/layui/layui.js"></script>';
        $str .= '<script>
                    layui.use([\'layer\'],function(){
                       layer.msg("' . $msg . '",{icon:"5",time:' . ($time * 1000) . '},function() {
                         ' . $url . '
                       });
                    })
                </script>';
        $response = think\Response::create($str, 'html');
    }
    throw new HttpResponseException($response);
}





/**
 * @name convertUnderline
 * @decs 下划线转驼峰
 * 关联model：
 * @param $str
 * @return string|string[]|null
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/5/10 23:00
 */
function convertUnderline($str)
{
    $str = preg_replace_callback('/([-_]+([a-z]{1}))/i',function($matches){
        return strtoupper($matches[2]);
    },$str);
    return $str;
}


/**
 * @name humpToLine
 * @decs 驼峰转下划线
 * 关联model：
 * @param $str
 * @return bool|string|string[]|null
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/5/10 23:00
 */
function humpToLine($str){
    //判断是小驼峰还是大驼峰 以大写开头的大驼峰
    if (preg_match('/^[A-Z]+/',$str)) {
        //判断有几个驼峰，
        if (preg_match_all('/[A-Z]/',$str)==1) {//只有一个只需大写换成小写
            $str=strtolower($str);
        }else{//先全部替换_小写，再去掉首的_
            //执行一个正则表达式搜索并且使用一个回调进行替换
            $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
                return '_'.strtolower($matches[0]);
            },$str);
            $str=substr($str,1);
        }
    }else{//小驼峰直接替换
        //执行一个正则表达式搜索并且使用一个回调进行替换
        $str = preg_replace_callback('/([A-Z]{1})/',function($matches){
            return '_'.strtolower($matches[0]);
        },$str);
    }

    return $str;
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

    return count($data) == 1 ? reset($data) : $data;
}

/**
 * 比较两个数组，合并且返回相应值
 * @author geeson 314835050@QQ.COM
 * @param array $arr1
 * @param array $arr2
 * @return array
 */
function diffArrayValue($arr1 = [], $arr2 = [])
{

    $difArr1 = array_diff_key($arr1, $arr2);
    $difArr2 = array_intersect_key($arr1, $arr2);
    $merge = array_merge($difArr1, $difArr2);
    foreach ($merge as $key => $value) {
        foreach ($arr2 as $key2 => $value2) {
            if ($key == $key2) {
                $merge[$key] = $value2;
            }
        }
    }
    return $merge;
}