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
        // var_dump($_REQUEST);
        auth_login([
            'name' => 'abc'
        ]);
        resp_success();
    }

    public function logout() {
        session_flush();
        redirect('/login');
    }
}