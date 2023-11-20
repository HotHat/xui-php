<?php

namespace App\Controller;

use App\Database\DB;

class LoginController
{
    public function login() {
        render('login.php', [
            'title' => '登录'
        ]);
    }

    public function submit() {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $user = DB::instance()->fetchOne('select rowid as id, * from user where username=?', [$username]);

        if (empty($user)) {
            respFail('登录失败');
        }

        if (!hashVerify($password, $user['password'])) {
            respFail('登录失败');
        }

        // session login
        authLogin($user);

        respSuccess();
    }

    public function logout() {
        sessionFlush();
        redirect('/login');
    }
}