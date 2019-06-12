<?php
/**
 * @name FieldType.php
 * @decs
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/6/11 23:20
 */


namespace app\mycms\admin;
use app\mycms\model\FieldType as FieldTypeModel;
use app\system\admin\Admin;
class FieldType extends Admin
{
    public function fieldList()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '字段类型列表');
        $this->view->assign('keywords', '字段类型列表关键字');
        $this->view->assign('description', '字段类型列表描述');

        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];
            /*按name匹配相关数据*/
            $list = FieldTypeModel::withSearch(['name'], ['name' => $data['key']])
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

    public function editFieldType()
    {

        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = [
                'ftid' => $this->request->param('ftid', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
            ];
            $object = new FieldTypeModel;
            if ($data['ftid']) {//编辑
                $res = $object->isUpdate()->save($data, ['ftid' => $data['ftid']]);
            } else {//新增
                //返回bool值
                unset($data['ftid']);
                $res = $object->save($data);
            }

            if ($res) {

                $this->success('保存成功', url('mycms/field_type/fieldList'));

            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出
            /*获取参数id*/
            $id = $this->request->param('ftid', 0, 'intval');

            /*根据id获取数据并进行数据处理*/
            if ($id) {
                $data = FieldTypeModel::where('ftid', '=', $id)->find();

                $this->assign('data', $data);
            }
            /*渲染对应模板*/
            return $this->fetch('form_field');
        }
    }
}