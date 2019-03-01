<?php

class AdminController extends BaseController{
    function phpinfo() {
        phpinfo();
        exit();
    }
}