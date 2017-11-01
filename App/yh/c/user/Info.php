<?php

declare(strict_types = 1);
namespace App\yh\c\user;
defined('IN_SYS') || exit('ACC Denied');

use App\yh\m;
use Gaara\Core\Controller\HttpController;

class Info extends HttpController {

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
