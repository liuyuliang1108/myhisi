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
use app\mycms\admin\Mymodel;
use app\mycms\model\Table as TableModel;
use app\system\admin\Admin;
use think\Db;
class Table extends Admin
{
  public function tableList(){

      /*SEO相关赋值*/
      $this->view->assign('title', '表格列表');
      $this->view->assign('keywords', '表格列表关键字');
      $this->view->assign('description', '表格列表描述');

      /*异步获取列表数据*/
      if ($this->request->isAjax()) {
          /*筛选配置信息*/
          $data = [
              'key' => $this->request->get('key', '', 'trim'),
              'limit' => $this->request->get('limit', 10, 'intval'),
          ];
          /*按name匹配相关数据*/
          $list = TableModel::withSearch(['name'], ['name' => $data['key']])
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
    public function edittable()
    {

        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = [
                'mid' => $this->request->param('id', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'remark' => $this->request->param('remark', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'attr' => $this->request->param('attr', 0, 'intval'),
            ];
            $object = new TableModel;
            if ($data['mid']) {//编辑
                $res = $object->isUpdate()->save($data, ['id' => $data['id']]);
            } else {//新增
                //返回bool值
                unset($data['mid']);
                $res = $object->save($data);
            }

            if ($res) {

                $this->success('保存成功', url('mycms/mytable/tableList'));

            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出
            /*获取参数id*/
            $id = $this->request->param('tid', 0, 'intval');

            /*根据id获取数据并进行数据处理*/
            if ($id) {
                $data = TableModel::where('tid', '=', $id)->find();

                $this->assign('data', $data);
            }
            /*渲染对应模板*/
            return $this->fetch('table_form');
        }
    }

    public function tableManage()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '字段列表');
        $this->view->assign('keywords', '字段列表关键字');
        $this->view->assign('description', '字段列表描述');

        $id = $this->request->param('tid', 0, 'intval');
        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];

            /*按name匹配相关数据*/
            $mid=TableModel::where('tid',$id)->value('mid');
            $tags=TableModel::where('tid',$id)->value('tags');
            $object = new ModelField;
            $list = $object->all(['mid' => $mid, 'tags' => $tags]);
            /*输出数据处理*/
            $res = [];
            foreach ($list as $key => $val) {
                $res[$key] = $val;
                $res[$key]['fieldOption']=Mymodel::fieldOption($val['id']);
            }

            return show($res, 0, '', ['count' => $list->count()]);
        }

        $this->assign('tid', $id);

        /*渲染对应模板*/
        return $this->fetch();
    }

    public function getCode()
    {
        //获取传入数据，并处理
        $id = $this->request->param('tid');
        $mid=TableModel::where('tid',$id)->value('mid');
        $tags=TableModel::where('tid',$id)->value('tags');

        $object = new ModelField;
        $list = $object->all(['mid' => $mid, 'tags' => $tags]);
        /*数据处理*/
        $res = [];
        foreach ($list as $val) {
            $res[] = $val->toArray();
        }

        array_multisort(array_column($res,'field_order'),SORT_ASC,$res);

        foreach ($res as $key=>$value){

        }
        /*渲染对应模板*/
        return $this->fetch();
    }
}