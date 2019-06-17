$res[$key]['{@name}Option']={@model}Model::{@name}Option($val['{@primary}']);


/**
     * @name {@name}Option
     * @decs 下拉列表
     * 关联model：
     * @param int ${@primary} 查询主键
    * @param string $str
     * @return string
     * @author 老猫 <18368091722@163.com>
     * Updated on: 2019/5/31 18:43
     */
    public static function {@name}Option(${@primary}, $str = '')
    {
        //获取目标模型数据集对象
        $list = {@targetModel}Model::all();
        //根据主键值查询对应外键id值
        ${@targetPrimary}=ModelField::where('{@primary}',${@primary})->value('{@targetPrimary}');

        foreach ($list as $v) { //目标模型数据集对象循环为目标模型

            if (${@targetPrimary} == $v['{@targetPrimary}']) { //当目标模型主键等于外键id时,设置为勾选
                $str .= '<option level="1" value="'.$v['{@targetPrimary}'].'" selected>['.$v['name'].']'.$v['title'].'</option>';
            } else {
                $str .= '<option level="1" value="'.$v['{@targetPrimary}'].'">['.$v['name'].']'.$v['title'].'</option>';
            }
        }
        return $str;
    }