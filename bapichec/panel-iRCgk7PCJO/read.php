<?php
require_once('vendor/autoload.php');

use Src\App\Main;
use Src\Config\Header;

Header::load();

$ip = $_GET['ipsread'];

$main = new Main();
echo $main->read($ip);