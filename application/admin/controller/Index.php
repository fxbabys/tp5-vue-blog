<?php
namespace app\admin\controller;
use think\Controller;

class Index extends Base {

    public function _initialize() {
        Parent::_initialize();
        if(!session('?user_name')) {
            return $this->redirect("index/index/index");
        }
    }

    public function admin() {
        $articles = model("index/articles")->where(['status'=>1])->paginate(5);
        $this->assign("articles", $articles);
        return view();
    }

    public function user() {
        $users = model("user")->paginate(5);
        // $this->assign("users", $users);
        return json($users);
    }

    public function addUser() {
        $id = input('id');
        if(!empty($id)) {
            $user = model("user")->getUser($id);
            $this->assign("user", $user);
        }
        return $this->fetch("addUser");
    }

    public function addAtc() {
        $id = input('id');
        if(!empty($id)) {
            $article = model("index/articles")->getOne($id);
            $category = model("index/category")->getOne($article["c_id"]);
            // $this->assign("article", $article);
            // $this->assign("category", $category);
            $data = $article;
        } else {
            $category = model("index/category")->getAll();
            // $this->assign("category", $category);
            $data = [
                'category' => $category
            ];
        }
        return json($data);
    }

    public function insertAtc() {
        $validate = new \app\admin\validate\User;
        $scene = input("scene");
        $id = input("post.id");
        $data = input("post.");
        // 获取u_id
        $u_id = session("u_id");
        $data['u_id'] = $u_id;
        $res = $validate->scene($scene)->check($data);
        if(!$res) {
            return $data = [
                'status' => 400,
                'info'   => $validate->getError()
            ];
        }
        if($id) {
            $res = model("index/articles")->allowField(true)->save($data, ['id'=>$id]);
            if(!$res) {
                return $data = [
                    'status' => 400,
                    'info'   => 'Update failure'
                ];
            }
        } else {
            $res = model("index/articles")->allowField(true)->save($data);
            if(!$res) {
                return $data= [
                    'status' => 400,
                    'info'   => 'Inserte failed',
                    'res'    => $res
                ];
            }
        }
        $data = [
            'status' => 200,
            'info'   => 'Opreation Success'
        ];
        return $data;
    }

    public function delUser() {
        $id = input("id");
        $user = new \app\admin\model\User;
        $res = $user::destroy($id);
        $this->redirect("index/user");
    }

    public function delAtc() {
        $id = input("id");
        $user = new \app\index\model\Articles;
        $res = $user::destroy($id);
        // $this->redirect("index/admin");
        if ($res) {
            $data = [
                'status' => 200,
                'info' => '删除成功'
            ];
        } else {
            $data = [
                'status' => 400,
                'info' => '删除失败'
            ];
        }
        return json($data);
    }

    public function userVali() {
        $validate = new \app\admin\validate\User;
        $scene = input("scene");
        $data = input("post.");
        $id = input("post.id");
        $oprea = input("post.oprea");
        $data["name"] = $data["user"];
        $data["pass"] = $data["pass"];
        $res = $validate->scene($scene)->check($data);
        if(!$res) {
            return $data = [
                'status' => 400,
                'info'   => $validate->getError()
            ];
        }
        if($oprea === "add" && model("user")->getName($data["user"])) {
            return $data = [
                'status' => 400,
                'info'   => "User Name repetition"
            ];
        }
        if($id) {
            $res = model("user")->allowField(true)->save($data, ['id'=>$id]);
            $info = "Updated Success";
        } else {
            $res = model("user")->allowField(true)->save($data);
            $info = "Add Success";
        }
        $data = [
            'status' => 200,
            'info'   => $info
        ];
        return $data;
    }

}


 ?>