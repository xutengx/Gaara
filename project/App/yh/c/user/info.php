<?php

namespace App\yh\c\user;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m;
use Main\Core\Controller\HttpController;

class info extends HttpController {

    public function select() {
        echo 'this is select';
    }

    public function update() {
        echo 'this is update';
    }

    public function destroy() {
        echo 'this is destroy';
    }

    public function create() {
        echo 'this is create';
    }
}
