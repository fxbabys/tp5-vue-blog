<?php
namespace app\index\controller;

use think\Controller;

class Index extends Base
{

    public function _initialize() {
        Parent::category();
    }

    public function _empty() {
        $this->redirect('index/index');
    }

    public function index() {
        return view();
    }

    public function getUserSession() {
        return session('user_name');
    }

    public function getStatusSession() {
        return session('u_status');
    }

    public function getNav() {
        return json(Parent::category());
    }

    public function article() {
        $id = input('id');
        $articles = model("articles")->getAtc($id);
        // $this->assign("articles", $articles);
        // return view();
        return json($articles);
    }

    public function detail() {
        $id = input('id');
        $detail = model("articles")->getOne($id);
        $this->assign("detail", $detail);
        return json($detail);
    }

    public function login() {
        $scene = input("scene");
        $this->assign("scene", $scene);
        return view();
    }

    public function loginCheck() {
        $validate = new \app\admin\validate\User;
        $scene = input("scene");
        $data = input("post.");
        $id = input("post.id");
        $data["name"] = $data["user"];
        $data["pass"] = $data["pass"];
        $res = $validate->scene($scene)->check($data);
        if(!$res) {  // 表单验证
            return $data = json([
                'status' => 400,
                'info'   => $validate->getError(),
                'scene'  => $data
            ]);
        }
        $user = model("admin/user")->getName($data["user"]);
        if($scene === "sign") {  // 注册
            if($user) {
                return $data = json([
                    'status' => 400,
                    'info'   => "User Name repetition"
                ]);
            }
            $res = model("admin/user")->allowField(true)->save($data);
            $info = "Sign Success";
            session("user_name", $data["name"]);
            cookie("user_name", $data["name"], 3600);
            session("u_id", $res);
            cookie("u_id", $res);
        } else {
            if(!$user) {
                return $data = json([
                    'status' => 400,
                    'info'   => "User Name doesn not exist"
                ]);
            } else if($data["pass"] !== $user["pass"]) {
                return $data = json([
                    'status' => 400,
                    'info'   => "Password error"
                ]);
            } else {
                $info = "Login Success";
                session('user_name', $data["name"]);
                cookie("user_name", $data["name"], 3600);
                session('u_id', $user["id"]);
                cookie("u_id", $user["id"], 3600);
                if($user["status"] == 2) {
                    session('u_status', 2);
                    cookie("u_status", 2, 3600);
                }
            }
        }
        $data = json([
            'status' => 200,
            'info'   => $info
        ]);
        return $data;
    }

    public function logout() {
        if (session('user_name')) {
            session(null);
            cookie('user_name',null);
            cookie('id', null);
            if(session("?u_status")) {
                cookie('u_status', null);
            }
            $data = [
                'status' => 200,
                'info'   => "Logout success"
            ];
        } else {
            $data = [
                'status' => 400,
                'info'   => "Logout failure"
            ];
        }
        return json($data);
    }
}
