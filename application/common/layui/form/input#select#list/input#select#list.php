//获取下拉列表
$model = new {@model}Model
$menu = $model->all()->order('sort desc')->column('{@name},name');
$menu[0] = '顶级菜单';
ksort($menu);
$this->assign('menu', $menu);//关联数组 键为value 值html显示
