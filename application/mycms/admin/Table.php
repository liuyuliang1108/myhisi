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
use app\system\model\SystemMenu as MenuModel;
use app\mycms\model\Mymodel as MymodelModel ;
use app\mycms\model\Form as FormModel;
use app\mycms\model\Element as ElementModel;
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
                'tid' => $this->request->param('tid', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'remark' => $this->request->param('remark', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'smid' => $this->request->param('smid', 0, 'intval'),
                'mid' => $this->request->param('mid', 0, 'intval'),
                'tags' => $this->request->param('tags', 0, 'intval'),
            ];
            $object = new TableModel;
            if ($data['tid']) {//编辑
                $res = $object->isUpdate()->save($data, ['tid' => $data['tid']]);
            } else {//新增
                //返回bool值
                unset($data['tid']);
                $res = $object->save($data);
            }

            if ($res) {

                $this->success('保存成功', url('mycms/table/tableList'));

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
            //获取系统菜单外键
            $menu= MenuModel::column('url','id');
            $menu=array_filter($menu);
            $this->assign('menu', $menu);

            //获取模型外键
            $midMenu= MymodelModel::column('name','mid');
            $midMenu=array_filter($midMenu);
            $this->assign('midMenu', $midMenu);
            /*渲染对应模板*/
            return $this->fetch('table_form');
        }
    }

    public function toolbarAdd()
    {

        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = [
                'eid' => $this->request->param('eid', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'mid' => $this->request->param('mid', 0, 'intval'),
                'tags' => $this->request->param('tags', 0, 'intval'),
                'json_data' => $this->request->param('json_data', '0', 'trim'),
            ];
            $data['json_data']=[0=>$data['json_data']];
            $tid=$this->request->param('tid', 0, 'intval');
            $object = new ModelField() ;

            $res = $object->save($data);

            if ($res) {

                $this->success('保存成功', url('mycms/table/tableManage',['tid'=>$tid]));

            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出
            /*获取参数id*/
            $tid = $this->request->param('tid', 0, 'intval');

            /*根据tid获取数据并进行数据处理*/
            if ($tid) {
                $data = TableModel::where('tid', '=', $tid)->find();

                $this->assign('data', $data);
            }


            //获取组件类型外键
            $eidMenu= ElementModel::where('ftid', '=', 10)->column('name','eid');
            $eidMenu=array_filter($eidMenu);
            $this->assign('eidMenu', $eidMenu);
            /*渲染对应模板*/
            return $this->fetch('');
        }
    }
    public function subjectAdd()
    {

        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
            //获取传入数据，并处理
            $data = [
                'eid' => $this->request->param('eid', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'mid' => $this->request->param('mid', 0, 'intval'),
                'tags' => $this->request->param('tags', 0, 'intval'),
                'json_data' => $this->request->param('json_data', '0', 'trim'),
            ];
            $data['json_data']=[0=>$data['json_data']];
            $tid=$this->request->param('tid', 0, 'intval');
            $object = new ModelField() ;

            $res = $object->save($data);

            if ($res) {

                $this->success('保存成功', url('mycms/table/tableManage',['tid'=>$tid]));

            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出
            /*获取参数id*/
            $tid = $this->request->param('tid', 0, 'intval');

            /*根据tid获取数据并进行数据处理*/
            if ($tid) {
                $data = TableModel::where('tid', '=', $tid)->find();

                $this->assign('data', $data);
            }
            /*获取参数项目标题*/
            $subjectTitle = $this->request->param('title', '', 'trim');
            $this->assign('subjectTitle', $subjectTitle);
            /*获取参数json数据提示*/
            $jsonTips = $this->request->param('jsonTips', '', 'trim');
            $this->assign('jsonTips', $jsonTips);
            //获取组件类型外键
            $eidMenu= ElementModel::where('ftid', '=', 10)->column('name','eid');
            $eidMenu=array_filter($eidMenu);
            $this->assign('eidMenu', $eidMenu);
            /*渲染对应模板*/
            return $this->fetch('');
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
        $smid = FormModel::where('fid', $id)->value('smid');

        $formInfo=MymodelModel::get(['mid'=>$mid]);
        $modelName=ucfirst(convertUnderline($formInfo['name']));
        //查询获得表主键字段
        $prefix=config('database.prefix');
        $tablename=$prefix.$formInfo['name'];
        $sql="SELECT column_name FROM INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` WHERE table_name='".$tablename. "' AND constraint_name='PRIMARY'";
        $primary=Db::query($sql);
        $primary=$primary[0]['column_name'];


        //查询获得表所在模块/控制器/方法等信息
        $url=MenuModel::where('id', $smid)->value('url');
        $urlArray=explode('/',$url);
        $module=$urlArray[0];
        $controller=$urlArray[1];
        $controller=ucfirst($controller);
        $method=$urlArray[2];
        $tableTitle=MenuModel::where('id', $smid)->value('title');

        $object = new ModelField;
        //获取本table字段数据集对象
        $list = $object->all(['mid' => $mid, 'tags' => $tags]);
        /*数据处理*/
        $res = [];
        foreach ($list as $val) {
            $res[] = $val->toArray();
        }
        //字段数据集排序
        array_multisort(array_column($res, 'field_order'), SORT_ASC, $res);

        $tablePath = APP_PATH . 'common/layui/table';
        $tableTplPath = $tablePath . '/table.html';
        $tableTpl = file_get_contents($tableTplPath);

        $flag = "#select-set#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $selectSet = substr($result[0], strlen($flag));

        $flag = "#table-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tableHeader = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $tableHeader= str_replace(['{@method}'], [$method],$tableHeader);

        $flag = "#toolbar-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $toolbarHeader = substr($result[0], strlen($flag));




        $flag = "#templet-button#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $templetButton = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $templetButton= str_replace(['{@controller}','{@primary}'], [$controller,$primary],$templetButton);

        $flag = "#toolbar-footer#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $toolbarFooter = substr($result[0], strlen($flag));

        $flag = "#module-load#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $moduleLoad = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $moduleLoad= str_replace(['{@controller}'], [$controller],$moduleLoad);

        $flag = "#table-render-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tableRenderHeader = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $tableRenderHeader= str_replace(['{@controller}'], [$controller],$tableRenderHeader);

        $flag = "#table-render-footer#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tableRenderFooter = substr($result[0], strlen($flag));

        $flag = "#toolbar-datatype-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $toolbarDatatypeHeader = substr($result[0], strlen($flag));

        $flag = "#toolbar-datatype-footer#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $toolbarDatatypeFooter = substr($result[0], strlen($flag));

        $flag = "#register-event#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $registerEvent = substr($result[0], strlen($flag));

        $flag = "#table-tool-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tableToolHeader = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $tableToolHeader= str_replace(['{@controller}'], [$controller],$tableToolHeader);

        $flag = "#table-tool-footer#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tableToolFooter = substr($result[0], strlen($flag));

        $flag = "#table-toolbar-header#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tabletoolbarHeader = substr($result[0], strlen($flag));
        //基础文件变量字符替换
        $tabletoolbarHeader= str_replace(['{@controller}'], [$controller],$tabletoolbarHeader);

        $flag = "#table-toolbar-footer#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tabletoolbarFooter = substr($result[0], strlen($flag));

        $flag = "#script-select#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $scriptSelect = substr($result[0], strlen($flag));

        $flag = "#table-footer#";
        $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
        preg_match($match, $tableTpl, $result);
        $tableFooter = substr($result[0], strlen($flag));


        $path=APP_PATH . 'common/layui';
        $colsContent='';
        $toolbarContent='';
        $templetContent='';
        $buttonTplContent='';
        $tableToolContent='';
        $buttonToolbarContent='';
        $tableToolbarContent='';
        $toolbarDatatypeContent='';
        $tableToolFlag=0;
        $tableToolbarFlag=0;
        $tableSelectFlag=0;
        $toolbarDatatypeFlag=0;
        $phpContent='';
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
                $tplname = $element->where(['eid' => $eid])->value('name');
                $tplPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $tplname . '.html';
                $tpl = file_get_contents($tplPath);
                switch ($eid) {

                    case 14://字段文本text
                        {
                            //基础文件变量字符替换
                            $colsContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $tpl);
                            break;
                        }
                    case 15://toolbar#add
                    case 16://toolbar#del
                        {
                            //基础文件变量字符替换
                            $toolbarContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}'], [$name, $title, $tips, $required,$controller,$tableTitle], $tpl);
                            break;
                        }
                    case 17://templet#switch
                        {
                            $flag = "#cols#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $cols = substr($result[0], strlen($flag));
                            $array = $jsonData[1] . '|' . $jsonData[0];
                            //基础文件变量字符替换
                            $colsContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}','{@array}'], [$name, $title, $tips, $required,$controller,$tableTitle,$array], $cols);

                            $flag = "#templet#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $templet = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $templetContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}','{@array}','{@primary}'], [$name, $title, $tips, $required,$controller,$tableTitle,$array,$primary], $templet);

                            break;
                        }
                    case 18://templet#button
                        {
                            $flag = "#cols#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $cols = substr($result[0], strlen($flag));
                            $url = $jsonData[0];
                            //基础文件变量字符替换
                            $colsContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}','{@primary}'], [$name, $title, $tips, $required,$controller,$tableTitle,$primary], $cols);

                            $flag = "#templet#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $templet = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $templetContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}','{@url}'], [$name, $title, $tips, $required,$controller,$tableTitle,$url], $templet);

                            break;
                        }
                    case 19://tool#button
                        {
                            $tableToolFlag++;
                            $flag = "#buttonTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $buttonTpl = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $buttonTplContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $buttonTpl);

                            $flag = "#table-tool#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $tableTool = substr($result[0], strlen($flag));
                            $url = $jsonData[0];
                            //基础文件变量字符替换
                            $tableToolContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}','{@url}'], [$name, $title, $tips, $required,$controller,$tableTitle,$url], $tableTool);
                            break;
                        }
                    case 20://toolbar#event
                        {
                            $tableToolbarFlag++;
                            $flag = "#htmlTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $htmlTpl = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $buttonToolbarContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $htmlTpl);

                            $flag = "#scriptTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $scriptTpl = substr($result[0], strlen($flag));
                            $url = $jsonData[0];
                            $scriptData = $jsonData[1];
                            //基础文件变量字符替换
                            $colsContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}','{@controller}','{@tableTitle}','{@url}','{@scriptData}'], [$name, $title, $tips, $required,$controller,$tableTitle,$url,$scriptData],$scriptTpl);
                            break;
                        }
                    case 21://table#select
                        {
                            $tableSelectFlag++;
                            $flag = "#htmlTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $htmlTpl = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $templetContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $htmlTpl);

                            $flag = "#scriptTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $scriptTpl = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $colsContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required],$scriptTpl);
                            break;
                        }
                    case 22://toolbar#datatype 注册事件
                        {
                            $toolbarDatatypeFlag++;
                            $flag = "#htmlTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $htmlTpl = substr($result[0], strlen($flag));
                            //基础文件变量字符替换
                            $buttonToolbarContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}'], [$name, $title, $tips, $required], $htmlTpl);

                            $flag = "#scriptTpl#";
                            $match = '/' . $flag . '[\W\w]*(?=' . $flag . ')/';
                            preg_match($match, $tpl, $result);
                            $scriptTpl = substr($result[0], strlen($flag));
                            $targetModel = $jsonData[0];
                            //查询获得表主键字段
                            $sql='SELECT column_name FROM INFORMATION_SCHEMA.`KEY_COLUMN_USAGE` WHERE table_name='.$formInfo['name']. 'AND constraint_name=`PRIMARY`';
                            $target=new $targetModel.'Model';
                            $targetPrimary=$target->query($sql);
                            //基础文件变量字符替换
                            $toolbarDatatypeContent .= str_replace(['{@name}', '{@title}', '{@tips}', '{@required}', '{@model}', '{@primary}', '{@targetModel}', '{@targetPrimary}'], [$name, $title, $tips, $required,$modelName,$primary,$targetModel,$targetPrimary],$scriptTpl);
                            break;
                        }
                }

            }

            $phpFlag = $element->where(['eid' => $eid])->value('php');
            if ($phpFlag) {
                $phpname = $element->where(['eid' => $eid])->value('name');
                $phpPath = $path . $element->where(['eid' => $eid])->value('dir') . '/' . $phpname . '.php';
                $php = file_get_contents($phpPath);
                switch ($eid) {

                    case 12://下拉列表select
                        {
                            //基础文件变量字符替换
                            $phpContent .= str_replace(['{@model}', '{@primary}'], [$modelName, $primary], $php);
                            break;
                        }
                }
            }
        }

        if (!$tableToolFlag) {
            $tableToolHeader='';
            $tableToolFooter='';
        }
        if (!$tableToolbarFlag) {
            $tabletoolbarHeader='';
            $tabletoolbarFooter='';
        }
        if (!$tableSelectFlag) {
            $selectSet='';
            $scriptSelect='';
        }
        if (!$toolbarDatatypeFlag) {
            $toolbarDatatypeHeader='';
            $toolbarDatatypeFooter='';
            $registerEvent='';
        }
        $tplHtml=$selectSet.$tableHeader.$toolbarHeader.$templetButton.$toolbarFooter.$moduleLoad.$tableRenderHeader.$colsContent.$tableRenderFooter.$toolbarDatatypeHeader.$toolbarDatatypeContent.$toolbarDatatypeFooter.$registerEvent.$tableToolHeader.$tableToolContent.$tableToolFooter.$tabletoolbarHeader.$tableToolbarContent.$tabletoolbarFooter.$scriptSelect.$tableFooter;

        $this->view->assign('tplHtml', $tplHtml);
        /*渲染对应模板*/
        return $this->fetch();

    }
}