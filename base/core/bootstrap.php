<?php
/**
 *  系统BootStrap 类
 *  作用: 错误初始化, 定义常量, 函数, 路由controller
 *
 */

class Zy_Core_Bootstrap {

    private function __construct() {}

    /**
     * 注册通用函数处理, 路径, 编码格式等常量
     * @return void
     */
    private function _initVariables () {

        // set error handler
        set_error_handler('Zy_Helper_Common::setErrorHandler');
        set_exception_handler('Zy_Helper_Common::setExceptionHandler');
        register_shutdown_function('Zy_Helper_Common::shutdownHandler');

        // load constants
        if ( !file_exists( SYSPATH . 'config/constants.php' ) ) {
            trigger_error('[Error] system initialization, [Detail] constants file not exsits');
        }
        require_once (SYSPATH . 'config/constants.php');

        // set charset-related stuff
        $charset = strtoupper(Zy_Helper_Config::getConfig('system', 'charset'));
        if (empty($charset)){
            trigger_error('[Error] system initialization, [Detail] charset empty');
        }

        ini_set('default_charset', $charset);

        if (extension_loaded('mbstring')) {
            define('MB_ENABLED', TRUE);
            mb_substitute_character('none');
        } else {
            define('MB_ENABLED', FALSE);
        }

        if (extension_loaded('iconv')) {
            define('ICONV_ENABLED', TRUE);
        } else {
            define('ICONV_ENABLED', FALSE);
        }
    }


    /**
     * 注册通用的路由规则
     * 访问路径为 host:port/APP_NAME/controller/actiona
     * 路由规则: controller/actions
     * @return
     */
    private function _initAutoRoute () {

        $uri_segment = Zy_Helper_URI::getSegmentUri() ;
        if (empty($uri_segment) || ! is_array($uri_segment)) {
            trigger_error ('[Error] router error [Detail] empty uri_segment'.$_SERVER['REQUEST_URI']);
        }

        if ($uri_segment['appname'] !== APP_NAME) {
            trigger_error ('[Error] router error [Detail] appname unequals');
        }

        $controller_path = $uri_segment['controller_path'];
        $controller_name = $uri_segment['controller_name'];
        $method          = $uri_segment['method'];

        if ( empty($controller_path) || empty($controller_name) || empty($method)) {
            trigger_error ('Error] router error [Detail] controller  or method empty');
        }

        if (! file_exists(BASEPATH.'controllers/' . $controller_path . '/' . $controller_name .'_controller.php')) {
            trigger_error ('Error] router error [Detail] file not found "' . $controller_path . ':' . $controller_name . '"');
        }

        require_once(BASEPATH.'controllers/' . $controller_path . '/' . $controller_name .'_controller.php');
        if ( !class_exists('Controller_' . $controller_name, FALSE) ) {
            trigger_error ('Error] router error [Detail] class not found "Controller_'.$controller_name .'"');
        }

        $controller = 'Controller_'.$controller_name;
        if (!method_exists($controller, $method)) {
            trigger_error ('Error] router error [Detail] method not found "Controller_'.$controller_name .':' . $method . ' "');
        }

        call_user_func([new $controller, '_init'], $method);
    }


    /**
     * start zy
     *
     * @static
     * @return  object
     */
    public function run() {
        $this->_initVariables ();
        $this->_initAutoRoute ();
    }

    private static $instance = NULL;

    public static function getInstance () {
        if ( self::$instance === NULL ) {
            self::$instance = new Zy_Core_Bootstrap();
        }
        return self::$instance ;
    }
}