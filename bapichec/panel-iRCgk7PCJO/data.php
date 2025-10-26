<?php
require_once 'vendor/autoload.php';
use Src\Config\Env;
$env = new Env();

$file_name = $env->getFilename();

if (file_exists($file_name)) {
    $current_data = file_get_contents($file_name);
    $array_data = json_decode($current_data, true);
}

function getData($array, $id)
{
    if (empty($array)) {
       return  $container = "<tr><td colspan='11'>No hay datos</td></tr>";
    }else{
        $container = [];
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]['Id'] == $id) {
               $container[] = $array[$i];
            }
        }
        return json_encode($container);
    }
}

if(isset($_POST["id1"])){
   echo getData($array_data,$_POST["id1"]);
}