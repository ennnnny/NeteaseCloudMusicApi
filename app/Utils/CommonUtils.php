<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */
namespace App\Utils;

class CommonUtils
{
    private $_PUBKEY = '65537';

    private $_NONCE = '0CoJUm6Qyw8W8jud';

    private $_MODULUS = '157794750267131502212476817800345498121872783333389747424011531025366277535262539913701806290766479189477533597854989606803194253978660329941980786072432806427833685472618792592200595694346872951301770580765135349259590167490536138082469680638514416594216629258349130257685001248172188325316586707301643237607';

    private $_IV = '0102030405060708';

    private $_LINUXAPIKEY = 'rFgB&h#%2?^eDg:Q';

    private $_EAPIKEY = 'e82ckenh8dichen8';

    /**
     * IP地址解析.
     * @param $ip_str
     * @return array
     */
    public function ip_parse($ip_str)
    {
        $mark_len = 32;
        if (strpos($ip_str, '/') > 0) {
            [$ip_str, $mark_len] = explode('/', $ip_str);
        }
        $ip = ip2long($ip_str);
        $mark = 0xFFFFFFFF << (32 - $mark_len) & 0xFFFFFFFF;
        $ip_start = $ip & $mark;
        $ip_end = $ip | (~$mark) & 0xFFFFFFFF;
        return [$ip, $mark, $ip_start, $ip_end];
    }

    /**
     * 生成16位随机字符串.
     * @param $length
     * @return string
     */
    public function randString($length)
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $result = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; ++$i) {
            $result .= $chars[rand(0, $max)];
        }
        return $result;
    }

    /**
     * 网易云音乐 weapi 加密数据.
     * @param $data
     * @return array
     */
    public function weApiRequest($data)
    {
        if (extension_loaded('bcmath')) {
            $secKey = $this->randString(16);
        } else {
            $secKey = 'B3v3kH4vRPWRJFfH';
        }

        $encText = base64_encode($this->aesEncrypt(base64_encode($this->aesEncrypt($data, 'cbc', $this->_NONCE, $this->_IV)), 'cbc', $secKey, $this->_IV));

        if (extension_loaded('bcmath')) {
//            $pow = $this->bchexdec((string)bin2hex(strrev($secKey)));
//            $encKeyMod = bcpowmod($pow, $this->_PUBKEY, $this->_MODULUS);
//            $encSecKey = $this->bcdechex($encKeyMod);
            $encSecKey = strrev(utf8_encode($secKey));
            $encSecKey = $this->bchexdec($this->str2hex($encSecKey));
            $encSecKey = bcpowmod($encSecKey, $this->_PUBKEY, $this->_MODULUS);
            $encSecKey = $this->bcdechex($encSecKey);
            $encSecKey = str_pad($encSecKey, 256, '0', STR_PAD_LEFT);
        } else {
            $encSecKey = '85302b818aea19b68db899c25dac229412d9bba9b3fcfe4f714dc016bc1686fc446a08844b1f8327fd9cb623cc189be00c5a365ac835e93d4858ee66f43fdc59e32aaed3ef24f0675d70172ef688d376a4807228c55583fe5bac647d10ecef15220feef61477c28cae8406f6f9896ed329d6db9f88757e31848a6c2ce2f94308';
        }

        return [
            'params' => $encText,
            'encSecKey' => $encSecKey,
        ];
    }

    /**
     * Linux的Api(网易云).
     * @param $data
     * @return array
     */
    public function linuxApi($data)
    {
        $text = json_encode($data);
        $eParams = strtoupper(bin2hex($this->aesEncrypt($text, 'ecb', $this->_LINUXAPIKEY, '')));
        return ['eparams' => $eParams];
    }

    /**
     * 安卓接口(网易云).
     * @param $url
     * @param $object
     * @return array
     */
    public function eApi($url, $object)
    {
        $text = json_encode($object);
        $message = 'nobody' . $url . 'use' . $text . 'md5forencrypt';
        $digest = md5($message);
        $data = $url . '-36cd479b6b5-' . $text . '-36cd479b6b5-' . $digest;
        $params = strtoupper(bin2hex($this->aesEncrypt($data, 'ecb', $this->_EAPIKEY, '')));
        return ['params' => $params];
    }

    /**
     * AES 证书加密.
     * @param $data
     * @param $mode
     * @param $secKey
     * @param $iv
     * @return false|string
     */
    public function aesEncrypt($data, $mode, $secKey, $iv)
    {
        if (function_exists('openssl_encrypt')) {
//            $cip = openssl_encrypt($data, 'aes-128-' . $mode, $secKey, OPENSSL_RAW_DATA, $iv);
            $cip = openssl_encrypt($data, 'aes-128-' . $mode, pack('H*', bin2hex($secKey)), OPENSSL_RAW_DATA, $iv);
        } else {
            $pad = 16 - strlen($data) % 16;
            $data = $data . str_repeat(chr($pad), $pad);
            $cip = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $secKey, $data, $mode, $iv);
        }
//        $cip = base64_encode($cip);
        return $cip;
    }

    /**
     * 解密.
     * @param $data
     * @return false|string
     */
    public function decrypt($data)
    {
        return openssl_decrypt($data, 'aes-128-ecb', pack('H*', bin2hex($this->_EAPIKEY)), OPENSSL_RAW_DATA, '');
    }

    /**
     * @param $dec
     * @return string
     */
    public function bcdechex($dec)
    {
        $hex = '';
        do {
            $last = bcmod($dec, '16');
            $hex = dechex($last) . $hex;
            $dec = bcdiv(bcsub($dec, $last), '16');
        } while ($dec > 0);
        return $hex;
    }

    /**
     * @param $hex
     * @return float|int|string
     */
    public function bchexdec($hex)
    {
        $dec = 0;
        $len = strlen($hex);
        for ($i = 1; $i <= $len; ++$i) {
            $dec = bcadd((string) $dec, bcmul(strval(hexdec($hex[$i - 1])), bcpow('16', strval($len - $i))));
        }

        return $dec;
    }

    /**
     * @param $string
     * @return string
     */
    private function str2hex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); ++$i) {
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0' . $hexCode, -2);
        }

        return $hex;
    }
}
