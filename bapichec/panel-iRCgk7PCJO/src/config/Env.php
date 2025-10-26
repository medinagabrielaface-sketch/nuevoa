<?php
namespace Src\Config;

class Env
{
    protected $countries;
    protected $chat_id;
    protected $token;
    protected $filename;
    protected $htaccess;
    protected $ip_block;

    public function __construct()
    {
        $this->countries = [
            'CO',
            'CR'
        ];

        $this->chat_id = '-XXXX';
        $this->token = 'XXXX';
        $this->filename = 'tentaculos.json';
        $this->htaccess = '../.htaccess';
        $this->ip_block = '../ips.txt';
    }

    public function getCountries()
    {
        return $this->countries;
    }

    public function getChatId()
    {
        return $this->chat_id;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function getFileName()
    {
        return $this->filename;
    }

    public function getHtaccess()
    {
        return $this->htaccess;
    }

    public function getIpBlockFile()
    {
        return $this->ip_block;
    }

    public function __dd($value)
    {
        echo "<pre>";
        var_dump($value);
        echo "</pre>";
    }
}