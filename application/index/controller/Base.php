<?php
namespace app\index\controller;
use think\Controller;

class Base extends Controller {

    public function _empty() {
        $this->redirect("index/index");
    }

    public function category() {
        $res = model("Category")->getAll();
        $this->assign('category',$res);

        $name = session('user_name');
        $this->assign('user_name',$name);

        if (cookie("?user_name") && !session("?user_name")) {
          session('user_name', cookie("user_name"));
          $this->redirect('Index/index');
        }
        return $res;
    }
}

?>