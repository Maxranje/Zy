<?php
/**
 *  公共函数库, 所有模块共用代码
 *  php 版本仅支持7.0以上
 */

class Zy_Helper_Common
{
    /**
     * 校验手机号是否合法
     *
     * @param string        $phone
     * @return boolean
     */
    public static function checkPhoneAvalilable($phone) {
        if( (0 >= $phone) || (10000000000 >= $phone) || (20000000000 <= $phone || !is_numeric($phone)) ) {
            return false;
        }
        return preg_match('#^1001[\d]{7}$|^13[\d]{9}$|^14[\d]{9}$|^15[^4]{1}\d{8}$|^16[\d]{9}$|^17\d{9}$|^18[\d]{9}|^19[\d]{9}$#', $phone) ? true : false;
    }


    // HTML实体字符编码
    public static function html_escape($str, $double_encode = TRUE)
    {
        if (empty($str))
        {
            return FALSE;
        }
        return htmlspecialchars($str, ENT_QUOTES, Zy_Helper_Config::getConfig('system', 'charset'), $double_encode);
    }

    // 检测是否是AJAX请求
    public static function is_Ajax()
    {
        return isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && strtolower($_SERVER["HTTP_X_REQUESTED_WITH"])=="xmlhttprequest";
    }

    // 重定向错误页面
    public static function redirect_error ($errno)
    {
        $serverdns = Zy_Helper_Config::getConfig('system', 'serverdns');
        self::http_redirect($serverdns . '/error.html?status=' . $errno) ;
    }

    // 重定向其他页面
    public static function http_redirect ($url, $status = 301)
    {
        self::set_header_status($status);
        header('Location: ' . $url) ;
        exit;
    }

    // 系统错误信息
    public static function setErrorHandler($errNo, $errStr, $filepath, $line)
    {
        $is_error = (((E_ERROR | E_PARSE | E_COMPILE_ERROR | E_CORE_ERROR | E_USER_ERROR) & $errNo) === $errNo);

        if (($errNo & error_reporting()) !== $errNo)
        {
            return;
        }

        $msg = sprintf("[%s] PHP Fatal error: %s in %s:%s \r\n", date('Y-m-d H:i:s', time()), $errStr, $filepath, $line);
        var_dump($msg);
        // error_log($msg, 3, Zy_Helper_Config::getConfig('system', 'log_path'));
        exit;
        //self::redirect_error(500);
    }

    public static function setExceptionHandler($e)
    {
        self::setErrorHandler( $e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine() );
    }

    public static function shutdownHandler()
    {
        $last_error = error_get_last();
        if (isset($last_error) && ($last_error['type'] & (E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING)))
        {
            self::setErrorHandler($last_error['type'], $last_error['message'], $last_error['file'], $last_error['line']);
        }
    }

    // 获取配置中mime类型
    public static function get_mimes()
    {
        static $_mimes;
        if (empty($_mimes))
        {
            $_mimes = file_exists(SYSPATH.'config/mimes.php') ? include(SYSPATH.'config/mimes.php') : array();
        }

        return $_mimes;
    }

    /**
     * 是否是HTTPS请求
     *
     * @return boolean
     */
    public static function is_https()
    {
        if ( ! empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) !== 'off')
        {
            return TRUE;
        }
        elseif (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) === 'https')
        {
            return TRUE;
        }
        elseif ( ! empty($_SERVER['HTTP_FRONT_END_HTTPS']) && strtolower($_SERVER['HTTP_FRONT_END_HTTPS']) !== 'off')
        {
            return TRUE;
        }

        return FALSE;
    }




    /**
     * 设置消息头状态码
     */
    public static function set_header_status ($code = 200, $text = '')
    {
        if (empty($code) OR ! is_numeric($code))
        {
            trigger_error ('Error] common error [Detail] set status header coder error');
        }

        if (empty($text))
        {
            is_int($code) OR $code = (int) $code;
            $stati = array(
                100 => 'Continue',
                101 => 'Switching Protocols',

                200 => 'OK',
                201 => 'Created',
                202 => 'Accepted',
                203 => 'Non-Authoritative Information',
                204 => 'No Content',
                205 => 'Reset Content',
                206 => 'Partial Content',

                300 => 'Multiple Choices',
                301 => 'Moved Permanently',
                302 => 'Found',
                303 => 'See Other',
                304 => 'Not Modified',
                305 => 'Use Proxy',
                307 => 'Temporary Redirect',

                400 => 'Bad Request',
                401 => 'Unauthorized',
                402 => 'Payment Required',
                403 => 'Forbidden',
                404 => 'Not Found',
                405 => 'Method Not Allowed',
                406 => 'Not Acceptable',
                407 => 'Proxy Authentication Required',
                408 => 'Request Timeout',
                409 => 'Conflict',
                410 => 'Gone',
                411 => 'Length Required',
                412 => 'Precondition Failed',
                413 => 'Request Entity Too Large',
                414 => 'Request-URI Too Long',
                415 => 'Unsupported Media Type',
                416 => 'Requested Range Not Satisfiable',
                417 => 'Expectation Failed',
                422 => 'Unprocessable Entity',
                426 => 'Upgrade Required',
                428 => 'Precondition Required',
                429 => 'Too Many Requests',
                431 => 'Request Header Fields Too Large',

                500 => 'Internal Server Error',
                501 => 'Not Implemented',
                502 => 'Bad Gateway',
                503 => 'Service Unavailable',
                504 => 'Gateway Timeout',
                505 => 'HTTP Version Not Supported',
                511 => 'Network Authentication Required',
            );

            if (isset($stati[$code]))
            {
                $text = $stati[$code];
            }
            else
            {
                trigger_error ('Error] common error [Detail] No status text available');
            }
        }

        $server_protocol = (isset($_SERVER['SERVER_PROTOCOL']) && in_array($_SERVER['SERVER_PROTOCOL'], array('HTTP/1.0', 'HTTP/1.1', 'HTTP/2'), TRUE))
            ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.1';

        header($server_protocol.' '.$code.' '.$text, TRUE, $code);
    }

    static public function rand_string($len=6,$type=0,$addChars='') {
        $str ='';
        switch($type) {
            case 0:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz'.$addChars;
                break;
            case 1:
                $chars= str_repeat('0123456789',3);
                break;
            case 2:
                $chars='ABCDEFGHIJKLMNOPQRSTUVWXYZ'.$addChars;
                break;
            case 3:
                $chars='abcdefghijkmnpqrstuvwxyz'.$addChars;//edited  zdf    去掉 o和l
                break;
            case 6://三个字母 + 一个数字   fixed iOS联想功能 造成验证码错误
                $chars='abcdefghijkmnpqrstuvwxyz'.$addChars;//edited  zdf    去掉 o和l
                break;
            default :
                // 默认去掉了容易混淆的字符oOLl和数字01，要添加请使用addChars参数
                $chars='ABCDEFGHIJKMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789'.$addChars;
                break;
        }
        if($len>10 ) {//位数过长重复字符串一定次数
            $chars= $type==1? str_repeat($chars,$len) : str_repeat($chars,5);
        }
        if($type==6){//新增3个字母+一个数字
            $chars   =   str_shuffle($chars);
            $str     =   substr($chars,0,$len-1);
            $str.= mt_rand(1,9);
        }else{
            $chars   =   str_shuffle($chars);
            $str     =   substr($chars,0,$len);
        }
        return $str;
    }

    public static function sendCaptchaMsg ($mobile, $code) {
        $api = 'https://dysmsapi.aliyuncs.com/?PhoneNumbers=%s&SignName=%s&TemplateCode=%s&TemplateParam=%s';
        $SignName       = "中鼎博雅";
        $TemplateCode   = 'SMS_153055065';
        $AccessKeyId    = 'LTAI4Fxy55DiXm1EwhqHAcnF';
        $TemplateParam  = json_encode(['code' => $code]);

        $api = sprintf($api, $mobile, $SignName, $TemplateCode, $AccessKeyId. $TemplateParam);

        return self::http($api, 'GET');
    }

    public static function http($url, $method = 'POST', $params = [], $header = [], $cookie = '', $userAgent = '', $option = [], & $httpcode = 0) {

        $request = Zy_Helper_Curl::getInstance();
        $request->setUrl($url);
        $request->setParams($params);
        $request->setMethod($method);
        $request->setHeader($header);
        $request->setCookie($cookie);
        $request->setOptions($option);
        $request->setUserAgent($userAgent);

        $request->exec();

        $result = $request->fetch();

        $httpcode = $request->httpInfo();

        $request->close();

        return $result;

    }
}