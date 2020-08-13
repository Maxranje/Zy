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
        if( (0 >= $phone) || (10000000000 >= $phone) || (20000000000 <= $phone) ) {
            return false;
        }
        $phonePrefix = intval(substr($phone, 0, 3));
        //以下为分运营商手机号开头规则
        $arrPhonePrefixs = array(
            'CMCC'    => array(134=>1, 135=>1, 136=>1, 137=>1, 138=>1, 139=>1, 150=>1, 151=>1, 152=>1,157=>1, 158=>1, 159=>1, 178=>1, 182=>1, 183=>1, 184=>1, 187=>1, 188=>1, 147=>1),
            'UNICOM'  => array(130=>1, 131=>1, 132=>1, 155=>1, 156=>1, 176=>1, 185=>1, 186=>1, 145=>1),
            'TELECOM' => array(133=>1, 153=>1, 173=>1, 177=>1, 180=>1, 181=>1, 189=>1),
        );
        foreach ($arrPhonePrefixs as $ISP => $prefixs) {
            if (isset($prefixs[$phonePrefix])) {
                return true;
            }
        }
        return false;
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
        error_log($msg, 3, Zy_Helper_Config::getConfig('system', 'log_path'));
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
    
}