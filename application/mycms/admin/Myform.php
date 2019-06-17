<?php
/**
 * @name Mymodel.php
 * @decs
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/5/31 11:20
 */


namespace app\mycms\admin;
use app\system\model\SystemMenu as MenuModel;
use app\mp\controller\App;
use app\mycms\model\Form;
use app\mycms\model\ModelField;
use app\mycms\admin\Mymodel;
use app\mycms\model\Mymodel as MymodelModel ;
use app\mycms\model\Form as FormModel;
use app\mycms\model\Element as ElementModel;
use app\system\admin\Admin;
use think\Db;

class Myform extends Admin
{
    public function formList()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '表单列表');
        $this->view->assign('keywords', '表单列表关键字');
        $this->view->assign('description', '表单列表描述');

        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];
            /*按name匹配相关数据*/
            $list = FormModel::withSearch(['name'], ['name' => $data['key']])
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

    public function editForm()
    {

        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = [
                'fid' => $this->request->param('fid', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'remark' => $this->request->param('remark', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'smid' => $this->request->param('smid', 0, 'intval'),
                'mid' => $this->request->param('mid', 0, 'intval'),
                'tags' => $this->request->param('tags', 0, 'intval'),
            ];
            $object = new FormModel;
            if ($data['fid']) {//编辑
                $res = $object->isUpdate()->save($data, ['fid' => $data['fid']]);
            } else {//新增
                //返回bool值
                unset($data['fid']);
                $res = $object->save($data);
            }

            if ($res) {

                $this->success('保存成功', url('mycms/myform/formList'));

            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出
            /*获取参数id*/
            $fid = $this->request->param('fid', 0, 'intval');

            /*根据fid获取数据并进行数据处理*/
            if ($fid) {
                $data = FormModel::where('fid', '=', $fid)->find();

                $this->assign('data', $data);
            }

            //获取系统菜单外键
            $menu= MenuModel::column('url','id');
            $menu=array_filter($menu);
            $this->assign('menu', $menu);

            //获取模型外键
            $midMenu= MymodelModel::column('name','mid');
            $midMenu=array_filter($midMenu);
            $this->assign('midMenu', $midMenu);

            /*渲染对应模板*/
            return $this->fetch('form_form');
        }
    }

    public function formManage()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '字段列表');
        $this->view->assign('keywords', '字段列表关键字');
        $this->view->assign('description', '字段列表描述');

        $id = $this->request->param('fid', 0, 'intval');
        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];

            /*按name匹配相关数据*/
            $mid = FormModel::where('fid', $id)->value('mid');
            $tags = FormModel::where('fid', $id)->value('tags');
            $object = new ModelField;
            $list = $object->all(['mid' => $mid, 'tags' => $tags]);
            /*输出数据处理*/
            $res = [];
            foreach ($list as $key => $val) {
                $res[$key] = $val;
                $res[$key]['fieldOption'] = Mymodel::fieldOption($val['id']);
            }

            return show($res, 0, '', ['count' => $list->count()]);
        }

        $this->assign('fid', $id);

        /*渲染对应模板*/
        return $this->fetch();
    }

    public function getCode()
    {
        //获取传入数据，并处理
        $id = $this->request->param('fid');
        $mid = FormModel::where('fid', $id)->value('mid');
        $tags = FormModel::where('fid', $id)->value('tags');
        $smid = FormModel::where('fid', $id)->value('smid');

        $formInfo=MymodelModel::get($mid);
        //查询获得表主键字段
        $sql='SELECT column_name FROM INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` WHERE table_name='.$formInfo['name']. 'AND constraint_name=`PRIMARY`';
        $mymodel=new MymodelModel;
        $primary=$mymodel->query($sql);

        //查询获得表所在模块/控制器/方法等信息
        $url=MenuModel::where('id', $smid)->value('url');
        $urlArray=explode('/',$url);
        $module=$urlArray[0];
        $controller=$urlArray[1];
        $method=$urlArray[2];
        $tableTitle=MenuModel::where('id', $smid)->value('title');

        //获取本table字段数据集对象
        $object = new ModelField;
        $list = $object->all(['mid' => $mid, 'tags' => $tags]);
        /*数据处理*/
        $res = [];
        foreach ($list as $val) {
            $res[] = $val->toArray();
        }

        array_multisort(array_column($res, 'field_order'), SORT_ASC, $res);
        $formPath = APP_PATH . 'common/layui/form';
        $formTplPath = $formPath . '/form.html';
        $formTpl = file_get_contents($formTplPath);
        $flag = "#form-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $formTpl, $result);
        $formHeader = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $formHeader= str_replace(['{@controller}'], [$controller],$formHeader);


        $flag = "#form-submit#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $formTpl, $result);
        $formSubmit = substr($result[0], strlen($flag));

        $flag = "#module-load#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $formTpl, $result);
        $moduleLoad = substr($result[0], strlen($flag));

        $flag = "#form-foot#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $formTpl, $result);
        $formFoot = substr($result[0], strlen($flag));

$path=APP_PATH . 'common/layui';
        $tplContent='';
        $phpContent='';
        $script='';
        foreach ($res as $key => $value) {
            $name = $value['name'];
            $title = $value['title'];
            $tips = $value['tips'];
            $jsonData = $value['json_data'];
            $eid = $value['eid'];
            $required = $value['required'] ? 'required' : '';
            //实例化对象
            $element = new ElementModel;
            $tplFlag = $element->where(['eid' => $eid])->value('tpl');
            if ($tplFlag) {
                switch ($eid) {

                    case 2://单行文本text
                    case 3://密码password
                    case 10://主键id
                    case 12://下拉列表select list
                        {
                            $tplname = $element->where(['eid' => $eid])->value('name');
                            $tplPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $tplname . '.html';
                            $tpl = file_get_contents($tplPath);
                            //基础文件变量字符替换
                            $tplContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $tpl);
                            break;
                        }
                    case 6://多行文本域textarea
                        {
                            $tplname = $element->where(['eid' => $eid])->value('name');
                            $tplPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $tplname . '.html';
                            $tpl = file_get_contents($tplPath);
                            $flag = "#form-item#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $formItem = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $tplContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $formItem);

                            $flag = "#script#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $formItem = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $script .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $formItem);

                            break;
                        }
                    case 7://开关switch
                        {
                            $tplname = $element->where(['eid' => $eid])->value('name');
                            $tplPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $tplname . '.html';
                            $tpl = file_get_contents($tplPath);
                            $array = $jsonData[1] . '|' . $jsonData[0];
                            //基础文件变量字符替换
                            $tplContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}', '{@array}'], [$name, $title, $tips, $required, $array], $tpl);
                            break;
                        }
                    case 8://单选radio
                        {
                            $tplname = $element->where(['eid' => $eid])->value('name');
                            $tplPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $tplname . '.html';
                            $tpl = file_get_contents($tplPath);
                            $list = '';
                            foreach ($jsonData as $k => $v) {
                                $list .= "'" . $k . "'=>'" . $v . "',";
                            }
                            //基础文件变量字符替换
                            $tplContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}', '{@list}'], [$name, $title, $tips, $required, $list], $tpl);
                            break;
                        }
                   /* case 9://下拉列表select
                        {
                            $tplname = $element->where(['eid' => $eid])->value('name');
                            $tplPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $tplname . '.html';
                            $tpl = file_get_contents($tplPath);
                            $flag = "#form-item#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $formItem = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $tplContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}', '{@primary}'], [$name, $title, $tips, $required, $primary], $formItem);

                            $flag = "#script#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $formItem = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $script .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $formItem);

                            break;
                        }*/
                }

            }

            $phpFlag = $element->where(['eid' => $eid])->value('php');
            if ($phpFlag) {
                switch ($eid) {

                    case 12://下拉列表select
                        {
                            $phpname = $element->where(['eid' => $eid])->value('name');
                            $phpPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $phpname . '.php';
                            $php = file_get_contents($phpPath);
                            //基础文件变量字符替换
                            $phpContent .= str_replace(['{@model}', '{@primary}'], [$mymodel, $primary], $php);
                            break;
                        }
                }
            }
        }
        $tplHtml=$formHeader.$tplContent.$formSubmit.$moduleLoad.$script.$formFoot;


        /*渲染对应模板*/
        return $this->fetch();
    }

    /**
     * @name getMenu
     * @decs 异步获取菜单信息
     * 关联model：
     * @return false|string
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 老猫 <18368091722@163.com>
     * Updated on: 2019/5/10 22:55
     */
    /*public function getMenu(){

        $id = $this->request->param('eid', 0, 'intval');

        $data = ElementModel::where('eid', '=', $id)->find();
        return json_encode($data);

    }*/
}