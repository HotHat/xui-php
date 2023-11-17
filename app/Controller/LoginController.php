<?php

namespace App\Controller;

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
        $user = \DB::instance()->fetchOne('select rowid as id, * from user where username=?', [$username]);

        if (empty($user)) {
            resp_fail('登录失败');
        }

        if (!hash_verify($password, $user['password'])) {
            resp_fail('登录失败');
        }

        // session login
        auth_login($user);

        resp_success();
    }

    public function logout() {
        session_flush();
        redirect('/login');
    }
}