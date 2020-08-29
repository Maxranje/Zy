<?php

class Zy_Helper_Strcrypt {

    const CRYPT_KEYA = 'd711e5723db5b03e';   // 密匙a会参与加解密 //'.>,df-zZ&';
    const CRYPT_KEYB = '8b912ff2723de14a';   // 密匙b会用来做数据完整性验证

    /**
     * 加密
     * @param $string
     * @return string
     */
    public static function encodeStr($string,$timeLength = 2) {
        if(!$string){
            return $string;
        }
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        // 密匙c用于变化生成的密文
        $timeKey = substr(md5(microtime()), -$timeLength);
        // 参与运算的密匙
        $cryptKey  = self::CRYPT_KEYA . md5(self::CRYPT_KEYA . $timeKey);
        $keyLength = strlen($cryptKey);

        // 明文，前16位用来保存keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        $string  = substr(md5($string . self::CRYPT_KEYB), 0, 16) . $string;
        $len     = strlen($string);
        $result  = '';
        $box     = range(0, 255);
        $randKey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $randKey[$i] = ord($cryptKey[$i % $keyLength]);
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $len; $i++) {
            $a       = ($a + 1) % 256;
            $j       = ($j + $box[$a] + $randKey[$a]) % 256;
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        return $timeKey . str_replace('=', '', base64_encode($result));

    }

    /**
     * 解密
     * @param $string
     * @return string
     */
    public static function decodeStr($string,$timeLength = 2,$expire = 0) {
        if(!$string){
            return $string;
        }
        // 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
        // 密匙c用于变化生成的密文
        $timeKey = substr($string, 0, $timeLength);
        // 参与运算的密匙
        $cryptKey  = self::CRYPT_KEYA . md5(self::CRYPT_KEYA . $timeKey);
        $keyLength = strlen($cryptKey);


        // 明文，前16位用来保存keyb(密匙b)，解密时会通过这个密匙验证数据完整性
        $string  = base64_decode(substr($string, $timeLength));
        $len     = strlen($string);
        $result  = '';
        $box     = range(0, 255);
        $randKey = array();
        // 产生密匙簿
        for ($i = 0; $i <= 255; $i++) {
            $randKey[$i] = ord($cryptKey[$i % $keyLength]);
        }
        // 核心加解密部分
        for ($a = $j = $i = 0; $i < $len; $i++) {
            $a       = ($a + 1) % 256;
            $j       = ($j + $box[$a] + $randKey[$a]) % 256;
            $tmp     = $box[$a];
            $box[$a] = $box[$j];
            $box[$j] = $tmp;
            // 从密匙簿得出密匙进行异或，再转成字符
            $result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
        }

        // 验证数据有效性，请看未加密明文的格式
        if($expire>0 && time()-$timeKey > $expire){
            return '';
        }else if (substr($result, 0, 16) == substr(md5(substr($result, 16) . self::CRYPT_KEYB), 0, 16)) {
            return substr($result, 16);
        } else {
            return '';
        }
    }
}