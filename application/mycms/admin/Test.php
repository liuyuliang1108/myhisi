<?php
/**
 * @name Test.php
 * @decs
 * @author 老猫 <18368091722@163.com>
 * Updated on: 2019/6/1 15:43
 */


namespace app\mycms\admin;

use app\system\admin\Admin;
class Test  extends Admin
{
 public function form(){
     /*渲染对应模板*/
     return $this->fetch();
 }
}