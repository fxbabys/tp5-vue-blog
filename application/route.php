<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
Route::rule([
    '/' => 'index/index/index',
    'getNav' => 'index/index/getNav',
    'getArts' => 'index/index/article',
    'getArtsNav/:id' => 'index/index/article',
    'getDetail/:id' => 'index/index/detail',
    'getUserSession' => 'index/index/getUserSession',
    'getStatusSession' => 'index/index/getStatusSession',
    'login' => 'index/index/login',
    'loginCheck' => 'index/index/loginCheck',
    'logout' => 'index/index/logout',
    'getAtc/:id' => 'admin/index/addAtc',
    'getUsers' => 'admin/index/user',
    'addUser' => 'admin/index/userVali',
    'addAtc' => 'admin/index/insertAtc',
    'atcDel' => 'admin/index/delAtc',
    'usrDel' => 'admin/index/delUser',
]);


// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];
