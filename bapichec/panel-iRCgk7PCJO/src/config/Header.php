<?php
namespace Src\Config;

class Header
{
    public static function load()
    {
        header("Access-Control-Allow-Origin: *");//dev
//         header("Access-Control-Allow-Origin: https://$_SERVER[HTTP_HOST]/");//production
        header("Access-Control-Allow-Headers: content-type");
        header("Access-Control-Allow-Methods: OPTIONS,GET,PUT,POST,DELETE");
    }

}