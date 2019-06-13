
//异步获取菜单信息
public function getMenu(){

$id = $this->request->param('{@primary}', 0, 'intval');

$data = {@model}Model::where('{@id}', '=', $id)->find();
return json_encode($data);

}