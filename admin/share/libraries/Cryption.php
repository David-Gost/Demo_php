<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Cryption
 *
 * @author David
 */
class Cryption {

    //加密
    public function encryption($public_key, $verification_code) {


        $pu_key = openssl_pkey_get_public($public_key); //判斷公鑰是否可用

        if (!$pu_key) {
            throw new Exception('Public Key invalid');
        }

        openssl_public_encrypt($verification_code, $encrypted, $pu_key);

        $encrypted = base64_encode($encrypted);
//        $encrypted = urlencode(str_replace("/", "_", $encrypted));

        return $encrypted;
    }

    //解密
    public function decryption($private_key, $encrypted_text) {

        $pi_key = openssl_pkey_get_private($private_key);

        openssl_private_decrypt(base64_decode($encrypted_text), $decrypted, $pi_key);

        return $decrypted;
    }

    //-----aes加密
    public function aes_encrypt($encrypt_key, $encrypt_from_str = '') {


        $cipher = "AES-128-CBC";

        $ivlen = openssl_cipher_iv_length($cipher);
        $iv = openssl_random_pseudo_bytes($ivlen);

        $ciphertext_raw = openssl_encrypt($encrypt_from_str, $cipher, $encrypt_key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $encrypt_key, $as_binary = true);
        return base64_encode($iv . $hmac . $ciphertext_raw);
    }

    //-----aes解密
    public function aes_decryption($encrypt_key, $encrypt_str) {


        $origin_cache_str = base64_decode($encrypt_str);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = substr($origin_cache_str, 0, $ivlen);
        $hmac = substr($origin_cache_str, $ivlen, $sha2len = 32);
        $ciphertext_raw = substr($origin_cache_str, $ivlen + $sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $encrypt_key, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $encrypt_key, $as_binary = true);
        if (hash_equals($hmac, $calcmac)) {
            return $original_plaintext;
        }
    }

    public function get_key_file($file_name) {

        if (preg_match('/admin/', $_SERVER['REQUEST_URI'])) {
            $file_path = './share/key/' . $file_name;
        } else {
            $file_path = './admin/share/key/' . $file_name;
        }

        $fp = fopen($file_path, "r", false);
        $key = fread($fp, 8192);
        fclose($fp);

        return $key;
    }

}
