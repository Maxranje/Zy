<?PHP
/**
 * ID 加密
 */

class Zy_Helper_Idcrypt {
    const NAPI_QUESTION_CRYPT_KEY = 'iVPed<7K';
    const NAPI_ARTICLE_CRYPT_KEY  = '^.vAy$TT';
    const QB_QUESTION_CRYPT_KEY   = 'lVPed<8K';
    const ZYB_CHARGE_CRYPT_KEY    = '^.vAy$TG';
    const ZYB_UID_CRYPT_KEY       = '^.vB>$TS';
    const ZYB_LECTURE_CRYPT_KEY   = '*)<~0YZS';
    const ZYB_OPENID_CRYPT_KEY    = '^.iC$eSC>';
    const MAGIC_NUM = 65521;
    const MAGIC_NUM2 = 65519;
    const INT32MAX = 4294967296;

    private static $blockSizeCache = array();

    /**
     *
     * (for 问作业)
     * 加密qid，从qid向qstr转换
     * @param int $qid 原始问题qid
     * @param int $type 0-napi_id 1-qb_id
     * @return string 加密后的str
     */
    public static function encodeQid ( $qid , $type = 0 ) {

        $qid = intval ($qid);
        $t = array($qid, $qid % self::MAGIC_NUM, 0, 0, $qid, $qid % self::MAGIC_NUM2, 0);
        if ($qid>=self::INT32MAX) {
            $str = pack ('NNnCCVVvC', $qid>>32, $qid%self::INT32MAX, $qid % self::MAGIC_NUM, 0, 0, $qid>>32, $qid%self::INT32MAX, $qid % self::MAGIC_NUM2, 0);
        } else {
            $str = pack ('NnCCVvC', $qid, $qid % self::MAGIC_NUM, 0, 0, $qid, $qid % self::MAGIC_NUM2, 0);
        }

        if(empty($type)) {
            $key = self::NAPI_QUESTION_CRYPT_KEY;
        } else {
            $key = self::QB_QUESTION_CRYPT_KEY;
        }

        // using PKCS#7 padding
        return bin2hex(openssl_encrypt($str, 'des-ecb', $key, OPENSSL_RAW_DATA));
    }

    /**
     * (for 问作业)
     * 解密qid，从qstr向qid转换
     * @param string $qstr 加密后的字符串
     * @return int 成功解密返回qid，失败返回0
     */
    public static function decodeQid ( $qstr , $type = 0 ) {

        $qstr = @hex2bin(strval($qstr));
        if($qstr === false) {
            return 0;
        }

        if(empty($type)) {
            $key = self::NAPI_QUESTION_CRYPT_KEY;
        } else {
            $key = self::QB_QUESTION_CRYPT_KEY;
        }

        $str = openssl_decrypt($qstr, 'des-ecb', $key, OPENSSL_RAW_DATA);

        if (strlen($str) == 23) {
            //64bit int
            $arr = @unpack ('Nqidtop/Nqid/ncheck/C2zero/Vqid2top/Vqid2/vcheck2/Czeropad', $str);
            $qid = $arr['qidtop']*self::INT32MAX + $arr['qid'];
            $qid2 =$arr['qid2top']*self::INT32MAX + $arr['qid2'];
        } else if (strlen($str) == 15) {
            //32bit int
            $arr = @unpack ('Nqid/ncheck/C2zero/Vqid2/vcheck2/Czeropad', $str);
            $qid = $arr['qid'];
            $qid2 =$arr['qid2'];
        }
        if ($arr['zero1'] != 0 || $arr['zero2'] != 0 || $arr['zeropad'] != 0) {
            return 0;
        }
        if ($qid% self::MAGIC_NUM === $arr['check'] && $qid2 % self::MAGIC_NUM2 === $arr['check2'] && $qid == $qid2) {
            return intval ($qid);
        } else {
            return 0;
        }
    }

    /**
     *
     * (for 学生圈)
     * 加密qid，从qid向qstr转换
     * @param int $qid 原始问题qid
     * @return string 加密后的str
     */
    public static function encodeAQid ($qid) {
        $qid = intval ($qid);
        $str = pack ('NnCCVvC', $qid, $qid % self::MAGIC_NUM, 0, 0, $qid, $qid % self::MAGIC_NUM2, 0);

        // 兼容以前bug，使用0做padding
        // $str = self::zeropadding($str, 'des', 'ecb');

        $bin = base64_decode(openssl_encrypt($str, 'des-ecb', self::NAPI_ARTICLE_CRYPT_KEY, OPENSSL_ZERO_PADDING));

        return bin2hex($bin);
    }

    /**
     *
     * (for 学生圈)
     * 解密qid，从qstr向qid转换
     * @param string $qstr 加密后的字符串
     * @return int 成功解密返回qid，失败返回0
     */
    public static function decodeAQid ($qstr) {

        $qstr = @hex2bin(strval($qstr));
        if($qstr === false) {
            return 0;
        }

        // 未截取padding, 对unpack无影响
        $str = openssl_decrypt(base64_encode($qstr), 'des-ecb', self::NAPI_ARTICLE_CRYPT_KEY, OPENSSL_ZERO_PADDING);

        $arr = @unpack ('Nqid/ncheck/C2zero/Vqid2/vcheck2/Czeropad', $str);
        if ($arr['zero1'] != 0 || $arr['zero2'] != 0 || $arr['zeropad'] != 0) {
            return 0;
        }
        if ($arr['qid'] % self::MAGIC_NUM === $arr['check'] && $arr['qid2'] % self::MAGIC_NUM2 === $arr['check2'] && $arr['qid'] == $arr['qid2']) {
            return intval ($arr['qid']);
        } else {
            return 0;
        }
    }

    /**
     *
     * (for charge订单系统)
     * 加密id，从id向str转换
     * @param int $id 订单号id
     * @return string 加密后的str
     */
    public static function encodeCid ($id) {
        $id = intval ($id);
        $str = pack ('NnCCVvC', $id, $id % self::MAGIC_NUM, 0, 0, $id, $id % self::MAGIC_NUM2, 0);

        // 兼容以前bug，使用0做padding
        // $str = self::zeropadding($str, 'des', 'ecb');

        $bin = base64_decode(openssl_encrypt($str, 'des-ecb', self::ZYB_CHARGE_CRYPT_KEY, OPENSSL_ZERO_PADDING));

        return bin2hex($bin);
    }

    /**
     *
     * (for charge订单系统)
     * 解密id，从str向id转换
     * @param string $str 加密后的字符串
     * @return int 成功解密返回id，失败返回0
     */
    public static function decodeCid ($str) {

        $str = @hex2bin(strval($str));
        if($str === false) {
            return 0;
        }

        // 未截取padding, 对unpack无影响
        $str = openssl_decrypt(base64_encode($str), 'des-ecb', self::ZYB_CHARGE_CRYPT_KEY, OPENSSL_ZERO_PADDING);

        $arr = @unpack ('Nqid/ncheck/C2zero/Vqid2/vcheck2/Czeropad', $str);
        if ($arr['zero1'] != 0 || $arr['zero2'] != 0 || $arr['zeropad'] != 0) {
            return 0;
        }
        if ($arr['qid'] % self::MAGIC_NUM === $arr['check'] && $arr['qid2'] % self::MAGIC_NUM2 === $arr['check2'] && $arr['qid'] == $arr['qid2']) {
            return intval ($arr['qid']);
        } else {
            return 0;
        }
    }

    /**
     * （for  1v1课程系统）
     * 加密id,从int到str转换
     * @param $id
     * @return string
     */
    public static function encodeLid ($id)
    {
        $id = intval($id);
        $str = pack('NnCCVvC', $id, $id % self::MAGIC_NUM, 0, 0, $id, $id % self::MAGIC_NUM2, 0);

        // 兼容以前bug，使用0做padding
        // $str = self::zeropadding($str, 'des', 'ecb');

        $bin = base64_decode(openssl_encrypt($str, 'des-ecb', self::ZYB_LECTURE_CRYPT_KEY, OPENSSL_ZERO_PADDING));

        return bin2hex($bin);
    }

    /**
     * (for 1v1课程系统)
     * 解密id，从str向id转换
     * @param $str
     * @return int
     */
    public static function decodeLid ($str)
    {
        $str = @hex2bin(strval($str));
        if ($str === false) {
            return 0;
        }
        // 未截取padding, 对unpack无影响
        $str = openssl_decrypt(base64_encode($str), 'des-ecb', self::ZYB_LECTURE_CRYPT_KEY, OPENSSL_ZERO_PADDING);
        $arr = @unpack('Nqid/ncheck/C2zero/Vqid2/vcheck2/Czeropad', $str);
        if ($arr['zero1'] != 0 || $arr['zero2'] != 0 || $arr['zeropad'] != 0) {
            return 0;
        }
        if ($arr['qid'] % self::MAGIC_NUM === $arr['check'] && $arr['qid2'] % self::MAGIC_NUM2 === $arr['check2'] && $arr['qid'] == $arr['qid2']) {
            return intval($arr['qid']);
        } else {
            return 0;
        }
    }


    /**
     *
     * (for 学生圈)
     * 加密qid，从qid向qstr转换
     * @param int $qid 原始问题qid
     * @return string 加密后的str
     */
    public static function encodeOuid ($qid) {
        $qid = intval ($qid);
        $str = pack ('NnCCVvC', $qid, $qid % self::MAGIC_NUM, 0, 0, $qid, $qid % self::MAGIC_NUM2, 0);

        // 兼容以前bug，使用0做padding
        // $str = self::zeropadding($str, 'des', 'ecb');

        $bin = base64_decode(openssl_encrypt($str, 'des-ecb', self::ZYB_OPENID_CRYPT_KEY, OPENSSL_ZERO_PADDING));

        return bin2hex($bin);
    }

    /**
     *
     * (for 学生圈)
     * 解密qid，从qstr向qid转换
     * @param string $qstr 加密后的字符串
     * @return int 成功解密返回qid，失败返回0
     */
    public static function decodeOuid ($qstr) {

        $qstr = @hex2bin(strval($qstr));
        if($qstr === false) {
            return 0;
        }

        // 未截取padding, 对unpack无影响
        $str = openssl_decrypt(base64_encode($qstr), 'des-ecb', self::ZYB_OPENID_CRYPT_KEY, OPENSSL_ZERO_PADDING);

        $arr = @unpack ('Nqid/ncheck/C2zero/Vqid2/vcheck2/Czeropad', $str);
        if ($arr['zero1'] != 0 || $arr['zero2'] != 0 || $arr['zeropad'] != 0) {
            return 0;
        }
        if ($arr['qid'] % self::MAGIC_NUM === $arr['check'] && $arr['qid2'] % self::MAGIC_NUM2 === $arr['check2'] && $arr['qid'] == $arr['qid2']) {
            return intval ($arr['qid']);
        } else {
            return 0;
        }
    }
    /**
     *
     * (for 作业帮账户系统UID)
     * 加密id，从id向str转换
     * @param int $id 用户uid
     * @return string 加密后的str
     */
    public static function encodeUid ($id) {
        $id = intval ($id);
        $str = pack ('NnCCVvC', $id, $id % self::MAGIC_NUM, 0, 0, $id, $id % self::MAGIC_NUM2, 0);

        // 兼容以前bug，使用0做padding
        // $str = self::zeropadding($str, 'des', 'ecb');

        $bin = base64_decode(openssl_encrypt($str, 'des-ecb', self::ZYB_UID_CRYPT_KEY, OPENSSL_ZERO_PADDING));

        return bin2hex($bin);
    }

    /**
     *
     * (for 作业帮账户系统UID)
     * 解密id，从str向id转换
     * @param string $str 加密后的字符串
     * @return int 成功解密返回id，失败返回0
     */
    public static function decodeUid ($str) {

        $str = @hex2bin(strval($str));
        if($str === false) {
            return 0;
        }

        // 未截取padding, 对unpack无影响
        $str = openssl_decrypt(base64_encode($str), 'des-ecb', self::ZYB_UID_CRYPT_KEY, OPENSSL_ZERO_PADDING);

        $arr = @unpack ('Nqid/ncheck/C2zero/Vqid2/vcheck2/Czeropad', $str);
        if ($arr['zero1'] != 0 || $arr['zero2'] != 0 || $arr['zeropad'] != 0) {
            return 0;
        }
        if ($arr['qid'] % self::MAGIC_NUM === $arr['check'] && $arr['qid2'] % self::MAGIC_NUM2 === $arr['check2'] && $arr['qid'] == $arr['qid2']) {
            return intval ($arr['qid']);
        } else {
            return 0;
        }
    }

    private static function zeropadding($data, $cipher = 'des', $mode = 'ecb') {

        // get block size
        $key = $cipher.'#'.$mode;

        if(empty(self::$blockSizeCache[$key])) {
            self::$blockSizeCache[$key] = mcrypt_get_block_size($cipher, $mode);
        }

        // padding
        $block = self::$blockSizeCache[$key];
        $pad = $block - (strlen($data) % $block);
        $data .= str_repeat("\0", $pad);

        return $data;
    }

}
