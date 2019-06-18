#funtionHeader#
public function {@method}()
    {

        if ($this->request->isPost()) { //ajax请求响应,保存:新增或编辑
            //获取传入数据，并处理
            $data = [
#funtionHeader#
                '{@primary}' => $this->request->param('{@primary}', 0, 'intval'),
                'name' => $this->request->param('name', '', 'trim'),
                'title' => $this->request->param('title', '', 'trim'),
                'remark' => $this->request->param('remark', '', 'trim'),
                'status' => $this->request->param('status', 1, 'intval'),
                'smid' => $this->request->param('smid', 0, 'intval'),
                'mid' => $this->request->param('mid', 0, 'intval'),
                'tags' => $this->request->param('tags', 0, 'intval'),

            ];
#dataSave#
            $object = new {@modelName}Model;
            if ($data['{@primary}']) {//更新
                $res = $object->isUpdate()->save($data, ['{@primary}' => $data['{@primary}']]);
            } else {//新增
                //返回bool值
                $res = $object->save($data);
            }

            if ($res) {

                $this->success('保存成功', url('{@controller}List'));

            } else {
                $this->error('保存失败');
            }
#dataSave#
#dataDeal#
        } else { //模板渲染输出
            /*获取参数id*/
            ${@primary} = $this->request->param('{@primary}', 0, 'intval');

            /*根据{@primary}获取数据并进行数据处理*/
            if (${@primary}) {
                $data = {@modelName}Model::where('{@primary}', '=', ${@primary})->find();

                $this->assign('data', $data);
            }
#dataDeal#
            //获取系统菜单外键
            $menu = MenuModel::column('url', 'id');
            $menu = array_filter($menu);
            $this->assign('menu', $menu);

            //获取模型外键
            $midMenu = MymodelModel::column('name', 'mid');
            $midMenu = array_filter($midMenu);
            $this->assign('midMenu', $midMenu);
#funtionFooter#
            /*渲染对应模板*/
            return $this->fetch('');
        }
    }
#funtionFooter#