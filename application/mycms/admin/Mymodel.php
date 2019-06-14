<?php
/**
 * @name Mymodel.php
 * @decs
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/5/31 11:20
 */


namespace app\mycms\admin;
use app\mp\controller\App;
use app\mycms\model\ModelField;
use app\mycms\model\FieldType as FieldTypeModel;
use app\mycms\model\Mymodel as HisiModel;
use app\system\admin\Admin;
use think\Db;
class Mymodel extends Admin
{
    public function modelList()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '模型列表');
        $this->view->assign('keywords', '模型列表关键字');
        $this->view->assign('description', '模型列表描述');

        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];
            /*按name匹配相关数据*/
            $list = HisiModel::withSearch(['name'], ['name' => $data['key']])
                ->hidden([''])
                ->paginate($data['limit'], false, ['query' => $data]);
            /*输出数据处理*/
            $res = [];
            foreach ($list as $key => $val) {
                $res[$key] = $val;
            }
            return show($res, 0, '', ['count' => $list->total()]);
        }

        /*渲染对应模板*/
        return $this->fetch();
    }

    public function fieldList()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '模型列表');
        $this->view->assign('keywords', '模型列表关键字');
        $this->view->assign('description', '模型列表描述');

        $id = $this->request->param('mid', 0, 'intval');
        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];

            /*按name匹配相关数据*/
            $object = new ModelField;
            $list = $object->all(['mid' => $id, 'tags' => 0]);
            /*输出数据处理*/
            $res = [];
            foreach ($list as $key => $val) {
                $res[$key] = $val;
                $res[$key]['fieldOption']=self::fieldOption($val['id']);
            }

            return show($res, 0, '', ['count' => $list->count()]);
        }

        $this->assign('mid', $id);

        /*渲染对应模板*/
        return $this->fetch();
    }

    /**
     * @name fieldOption
     * @decs 下拉列表
     * 关联model：
     * @param string $str
     * @return string
     * @author 老猫 <18368091722@163.com>
     * Updated on: 2019/5/31 18:43
     */
    public static function fieldOption($id, $str = '')
    {
        $list = FieldTypeModel::all();
        $ftid=ModelField::where('id',$id)->value('ftid');

        foreach ($list as $v) {

            if ($ftid == $v['ftid']) {
                $str .= '<option level="1" value="'.$v['ftid'].'" selected>['.$v['name'].']'.$v['title'].'</option>';
            } else {
                $str .= '<option level="1" value="'.$v['ftid'].'">['.$v['name'].']'.$v['title'].'</option>';
            }
        }
        return $str;
    }

    public function initField()
    {
        //获取传入参数
        $id = $this->request->param('mid', 0, 'intval');
        if ($id) {
            //获取模型名（不含前缀）
            $model = HisiModel::get(['mid' => $id])->name;
            // 获取该表字段信息
            $res = Db::name($model);
            //获取到该表所有字段
            $fields = $res->getTableFields();
            //获取数据库配置
            $prefix = config('database.prefix');
            $database = config('database.database');
            foreach ($fields as $key => $value) {
                $list['name'] = $value;
                $result = get_db_column_comment($prefix . lcfirst($model), $value, $database, 1);
                $list['title'] = is_array($result) ? end($result) : $result;
                $list['json_data'] = is_array($result) ? array_pop($result) : 0;
                $list['json_data'] = is_array($result) ? $result : 0;
                $list['attr'] = 0;//初始字段参数
                $list['field_order'] = $key;//初始字段排序信息
                $list['status'] = 1;//初始字段状态
                $list['mid'] = $id;//关联模型外键
                //将数据存入数据库
                $object = new ModelField();
                //返回bool值
                $res = $object->save($list);
            }
            if ($res) {
                return '初始化表字段成功';
            }

        } else {
            $this->error('非法请求');
        }
    }


    public function editModel()
    {

        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = [
                'mid' => $this->request->param('mid', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'remark' => $this->request->param('remark', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'attr' => $this->request->param('attr', 0, 'intval'),
            ];
            $object = new HisiModel;
            if ($data['mid']) {//编辑
                $res = $object->isUpdate()->save($data, ['mid' => $data['mid']]);
            } else {//新增
                //返回bool值
                unset($data['mid']);
                $res = $object->save($data);
            }

            if ($res) {

                $this->success('保存成功', url('mycms/mymodel/modelList'));

            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出
            /*获取参数id*/
            $id = $this->request->param('id', 0, 'intval');

            /*根据id获取数据并进行数据处理*/
            if ($id) {
                $data = HisiModel::where('mid', '=', $id)->find();

                $this->assign('data', $data);
            }
            //获取下拉列表
            $result=Db::query("SELECT `TABLE_NAME` FROM information_schema.TABLES WHERE `TABLE_SCHEMA` = 'hisi' AND `TABLE_TYPE` = 'BASE TABLE';");
$prefix=config('database.prefix');
$menu=[];
foreach($result as $k=>$v){
    $str=str_replace($prefix,"",$v['TABLE_NAME']);
    $menu[$str]=$str;
}
ksort($menu);
$this->assign('menu', $menu);
            /*渲染对应模板*/
            return $this->fetch('model_form');
        }
    }

    public function editField()
    {
        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = $this->request->param('data');
            $object = new ModelField();
            if ($data['id']) {//编辑
                $res = $object->isUpdate()->save($data, ['id' => $data['id']]);
            }
            if ($res) {

                $this->success('保存成功', url('mycms/mymodel/modelList'));

            } else {
                $this->error('保存失败');
            }
        } else {
            $this->error('非法请求');
        }
    }
    public function tags()
    {
        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = $this->request->param('data');
            $mid=$this->request->param('mid');
            $object = new ModelField();
            //获取tags
            $tags=$object->where('mid',$mid)->max('tags')+1;

            foreach ($data as $value){
                $value['tags']=$tags;
                unset($value['id']);
                unset($value['fieldOption']);
                $newData[]=$value;
            }
            $res = $object->saveAll($newData);
            if ($res) {

                $this->success('保存成功', url('mycms/mymodel/modelList'));

            } else {
                $this->error('保存失败');
            }
        } else {
            $this->error('非法请求');
        }
    }

}