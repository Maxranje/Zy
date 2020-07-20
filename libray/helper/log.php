<?php
/**
 * 输出LOG到对应工程下的 APPPATH . logs/下
 */
class Zy_Helper_Log {

    /**
     * Path to save log files
     *
     * @var string
     */
    protected $_log_path;

    /**
     * File permissions
     *
     * @var int
     */
    protected $_file_permissions = 0644;

    /**
     * Whether or not the logger can write to the log files
     *
     * @var bool
     */
    protected $_enabled     = TRUE;

    /**
     * Predefined logging levels
     *
     * @var array
     */
    protected $_levels      = array(
        'FAITAL'    => 1,
        'WARNING'   => 2,
        'NOTICE'    => 3,
        'ALL'       => 4
    );

    /**
     * Format of timestamp for log files
     *
     * @var string
     */
    protected $_date_fmt = "Y-m-d H:i:s";

    /**
     *  const warning and notice formate
     */
    const LOG_WARNING_FORMAT = "WARNING: %s [%s]  uri [%s]  refer [%s]  client_ip [%s]  module [%s]  uid [0] %s \r\n";
    const LOG_NOTICE_FORMAT  = "NOTICE: %s  reuqest_uri [%s] refer [%s]  client_ip [%s]  module [%s]  uid [0] %s \r\n";

    /**
     * Log instance
     */
    private static $instance  = NULL;

    // --------------------------------------------------------------------

    /**
     * Class constructor
     *
     * @return  void
     */
    private function __construct()
    {
        $log_path = Zy_Helper_Config::getConfig('system', 'log_path');

        $this->_log_path = ($log_path !== '') ? $log_path : '/var/log/php-fpm/php-fpm.log' ;
        file_exists($this->_log_path) OR mkdir($this->_log_path, 0755, TRUE);

        if ( ! is_dir($this->_log_path) OR ! is_writable($this->_log_path))
        {
            $this->_enabled = FALSE;
        }
    }

    public static function getInstance ()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new Zy_Helper_Log();
        }
        return self::$instance;
    }

    public static function warning($message)
    {
        $log = self::getInstance();
        $log->write_log("WARNING", $message);
    }

    public static function addnotice($message)
    {
        $log = self::getInstance();
        $log->write_log("NOTICE", $message);
    }


    /**
     * Write Log File
     */
    private function write_log($level, $msg)
    {
        if ($this->_enabled === FALSE)
        {
            return FALSE;
        }

        if (( ! isset($this->_levels[$level]) ))
        {
            return FALSE;
        }

        if ( ! file_exists($this->_log_path))
        {
            $newfile = TRUE;
        }

        if ( ! $fp = @fopen($this->_log_path, 'ab'))
        {
            return FALSE;
        }

        fwrite($fp, $this->_format_line($level, $msg));

        flock($fp, LOCK_UN);
        fclose($fp);

        if (isset($newfile) && $newfile === TRUE)
        {
            chmod($this->_log_path, $this->_file_permissions);
        }

        return true;
    }

    // --------------------------------------------------------------------

    /**
     * Format the log line.
     *
     * This is for extensibility of log formatting
     * If you want to change the log format, extend the CI_Log class and override this method
     *
     * @param   string  $level  The error level
     * @param   string  $date   Formatted date string
     * @param   string  $message    The log message
     * @return  string  Formatted log line with a new line character '\n' at the end
     */
    protected function _format_line($level, $message)
    {

        $uri         = $_SERVER['PHP_SELF'];
        $refer       = $_SERVER['HTTP_REFERER'];
        $client_ip   = $_SERVER['REMOTE_ADDR'];
        $date        = date($this->_date_fmt);

        if ($this->_levels[$level] == 1 || $this->_levels[$level] == 2)
        {
            $trace = debug_backtrace();
            $trace = count($trace) > 0 && is_array($trace) ? $trace[0] : array();
            $currentFile = isset($trace['file']) ? $trace['file'] . ":" . $trace['line'] : "";

            $message = sprintf(self::LOG_WARNING_FORMAT, $date, $currentFile, $uri, $refer, $client_ip, APP_NAME, $message);
        }
        else
        {
            $message = sprintf(self::LOG_NOTICE_FORMAT, $date, $uri, $refer, $client_ip, APP_NAME, $message);
        }

        return $message;
    }


    // --------------------------------------------------------------------

    /**
     * Byte-safe strlen()
     *
     * @param   string  $str
     * @return  int
     */
    protected static function strlen($str)
    {
        return mb_strlen($str, '8bit');
    }

    // --------------------------------------------------------------------

    /**
     * Byte-safe substr()
     *
     * @param   string  $str
     * @param   int $start
     * @param   int $length
     * @return  string
     */
    protected static function substr($str, $start, $length = NULL)
    {
        if (true)
        {
            // mb_substr($str, $start, null, '8bit') returns an empty
            // string on PHP 5.3
            isset($length) OR $length = ($start >= 0 ? self::strlen($str) - $start : -$start);
            return mb_substr($str, $start, $length, '8bit');
        }

        return isset($length)
            ? substr($str, $start, $length)
            : substr($str, $start);
    }
}
