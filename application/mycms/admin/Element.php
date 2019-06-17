<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/17
 * Time: 21:20
 */
namespace app\mycms\admin;

use app\system\admin\Admin;
use app\mycms\model\FieldType as FieldTypeModel;
use app\mycms\model\Element as ElementModel;
use think\Request;

class Element extends Admin
{
    public function menu()
    {
        if ($this->request->isPost()) {
            $list = ElementModel::order('sort desc')->select();
            return show($list,0,'获取成功');
        }
        return $this->fetch();
    }

    public function editMenu()
    {
        if ($this->request->isPost()) { //ajax请求响应:编辑或新增
        //获取传入数据，并处理
            $data = [
                'subject' => $this->request->post('subject', '', 'trim'),
                 'name' => $this->request->post('name', '', 'trim'),
                'title' => $this->request->post('title', '', 'trim'),
                'pid' => $this->request->post('pid', 0, 'intval'),
                'status' => $this->request->post('status', 0, 'intval'),
                'js' => $this->request->post('js', 0, 'intval'),
                'tpl' => $this->request->post('tpl', 0, 'intval'),
                'php' => $this->request->post('php', 0, 'intval'),
                'ftid' => $this->request->post('ftid', 0, 'intval'),
            ];
            $pId=$data['pid'];
            $result=[];
            while ($pId){
                $res=ElementModel::get(['eid'=>$pId]);
                $result[$pId]=$res->name;
                $pId=$res->pid;
            }
            $dir='';
            //先将数组从小到大排列，再遍历出目录地址
            ksort($result);
            foreach ($result as $value){
                $dir.='/'.$value;
            }
            $data['dir']=$dir.'/'.$data['name'];
            $id = $this->request->post('eid', 0, 'intval');

        //如果id为true则为编辑，否则为新增
            if (!$id) {
                $res = ElementModel::create($data);
            } else {
                $res = ElementModel::where('eid', '=', $id)->update($data);
            }
            if ($res) {
                $this->success('保存成功', url('mycms/element/menu'));
            } else {
                $this->error('保存失败');
            }
        } else { //模板渲染输出

            $id = $this->request->param('eid', 0, 'intval');
            if ($id) {
                $data = ElementModel::where('eid', '=', $id)->find();
                $this->assign('data', $data);
            }
        //获取菜单列表
            $menu = ElementModel::where('pid', '=', 0)->order('sort desc')->column('eid,name');
            $menu[0] = '顶级菜单';
            ksort($menu);
            $this->assign('menu', $menu);

            //获取模型外键列表
            $ftidMenu= FieldTypeModel::column('name','ftid');
            $ftidMenu=array_filter($ftidMenu);
            $this->assign('ftidMenu', $ftidMenu);
            return $this->fetch();
        }
    }


    public function elementBuild(){
        $id = $this->request->param('eid', 0, 'intval');
        if ($id) {
            $data = ElementModel::get(['eid'=>$id]);
            $subject=$data->subject;
            $dir=APP_PATH . 'common/'.$subject;
            $dir=$dir.$data->dir;

            if ($data->tpl) {
                $filename[]=$dir.'/'.$data->name.'.html' ;
            }
            if ($data->js) {
                $filename[]=$dir.'/'.$data->name.'.js' ;
            }
            if ($data->php) {
                $filename[]=$dir.'/'.$data->name.'.php' ;
            }
            //判断写入文件目录是否存在
            if(!is_dir($dir)){
                mkdir($dir);
            }

            if ($data->tpl) {
                        $content[] = '{$field}字段名'."\n". '{$comment}注释'."\n". '{$array}单选数组字符串'."\n";

            }
            if ($data->php) {
                $content[] = '{$field}字段名'."\n". '{$comment}注释'."\n". '{$model}模型名'."\n";
            }
            foreach ($filename as$key=> $value){
                //写入文件
                file_put_contents($value, $content[$key].PHP_EOL, FILE_APPEND);
            }
        }
        $this->success('生成成功');
    }

    /**
     * @name menuList
     * @decs 异步获取菜单列表
     * 关联model：Element
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     * @author 老猫 <18368091722@163.com>
     * Updated on: 2019/5/10 22:57
     */
    public function menuList()
    {
        $list = ElementModel::field('id,pid,name as text')->select();
        $data = list_to_tree($list->toArray(), 'id', 'pid', 'children');
        return $data;
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
    public function getMenu(){

        $id = $this->request->param('eid', 0, 'intval');

        $data = ElementModel::where('eid', '=', $id)->find();
        return json_encode($data);

    }
}



