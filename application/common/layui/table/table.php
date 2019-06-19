#funtionHeader#
public function {@method}()
    {

        /*SEO相关赋值*/
        $this->view->assign('title', '{@tableTitle}');
        $this->view->assign('keywords', '{@tableTitle}关键字');
        $this->view->assign('description', '{@tableTitle}描述');

        ${@soPrimary} = $this->request->param('{@soPrimary}', 0, 'intval');
        /*异步获取列表数据*/
        if ($this->request->isAjax()) {
#funtionHeader#
#laypage#
            /*筛选配置信息*/
            $data = [
                'key' => $this->request->get('key', '', 'trim'),
                'limit' => $this->request->get('limit', 10, 'intval'),
            ];
#laypage#
#selectData#
            /*按name匹配相关数据*/
            $mid=TableModel::where('tid',$id)->value('mid');
            $tags=TableModel::where('tid',$id)->value('tags');
            $object = new ModelField;
            $list = $object->all(['mid' => $mid, 'tags' => $tags]);
#selectData#
#dataDeal#
            /*输出数据处理*/
            $res = [];
            foreach ($list as $key => $val) {
                $res[$key] = $val;
                $res[$key]['fieldOption']=Mymodel::fieldOption($val['id']);
                $res[$key]['json_data']=implode("|",$res[$key]['json_data']);
            }
#dataDeal#
#funtionFooter#
            return show($res, 0, '', ['count' => $list->count()]);
        }
        $this->assign('{@soPrimary}', ${@soPrimary});

        /*渲染对应模板*/
        return $this->fetch();
    }
#funtionFooter#
