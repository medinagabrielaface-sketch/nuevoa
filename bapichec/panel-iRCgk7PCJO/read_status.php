<?php
require_once('vendor/autoload.php');
use Src\App\Main;
use Src\Config\Header;

Header::load();

if (count($_REQUEST)) {
    $main = new Main();
    echo $main->get_status($_REQUEST);
}

?>