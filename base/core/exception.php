<?php
/**
 * @brief 通用异常处理流程
 *
 *
 * @filesource zy/base/Exception.php
 * @version 1.0.0
 * @date    2018-06-11
 */
class Zy_Core_Exception extends Exception {

    const TRACE   = 'trace';
    const DEBUG   = 'debug';
    const NOTICE  = 'notice';
    const WARNING = 'warning';
    const FATAL   = 'fatal';

    protected $ec;
    protected $em;        
    protected $arg;

    /**
     * @param int    $ec      传入的错误号
     * @param string $em     附加信息
     * @param array  $arg        上下文
     * @param string $level      日志打印级别
     * @return void
     */
    public function __construct($ec, $em = '', $arg = array(), $level = self::WARNING) {
        $this->ec   = $ec;
        $this->em   = $em;
        $this->arg  = $arg;

        if (empty($this->arg) || !is_array($this->arg)) {
            $this->arg = array();
        }
        $this->arg = json_encode($this->arg);

        $stackTrace   = $this->getTrace();
        $class        = @$stackTrace[0]['class'];
        $type         = @$stackTrace[0]['type'];
        $function     = @$stackTrace[0]['function'];
        $file         = $this->file;
        $line         = $this->line;

        if (null != $class) {
            $function = "{$class}{$type}{$function}";
        }

        if (empty($level)) {
            $level    = self::WARNING;
        }

        Zy_Helper_Log::$level("{$this->em} at [{$function} in {$file}:{$line}] content-text:[{$this->arg}]");
        parent::__construct($this->em, $this->ec);
    }

    public function getErrNo() {
        return $this->ec;
    }

    public function getErrStr() {
        return $this->em;
    }

    public function getErrArg() {
        return $this->arg;
    }
}
