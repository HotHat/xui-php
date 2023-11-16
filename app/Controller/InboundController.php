<?php

namespace App\Controller;

class InboundController
{
    public function __construct()
    {
        check_login();
    }

    public function index() {
        render('xui/inbounds.php');
    }

}