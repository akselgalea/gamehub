<?php

namespace App\Services;

class EncryptService
{

    private $encrypt_method = 'AES-256-CBC';
    private $secret_key = 'This is my secret key';
    private $secret_iv = 'This is my secret iv';
    private $key;
    private $iv;

    public function __construct() {
        $this->key = hash('sha256', $this->secret_key);
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $this->iv = substr(hash('sha256', $this->secret_iv), 0, 16);
    }

    function encrypt($string) {
        $output = openssl_encrypt($string, $this->encrypt_method, $this->key, 0, $this->iv);
        $output = base64_encode($output);
    
        return $output;
    }

    function decrypt($string) {
        $output = openssl_decrypt(base64_decode($string), $this->encrypt_method, $this->key, 0, $this->iv);

        return $output;
    }
}