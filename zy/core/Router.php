<?php

class Zy_Router {
    private $action;

    private $controller;

    public function __construct() {
        $this->_initDefaultVariables ();
        $this->_initAutoRoute ();
    }

    /**
     * 注册通用函数处理, 路径, 编码格式等常量
     * @return
     */
    private function _initDefaultVariables () {

        // define path
        $template_path      = 'template/' . APP_NAME;
        $application_path   = 'application' . APP_NAME;

        if ( ($app = realpath($application_path)) && ($tmp = realpath($template_path)) ) {
            define('APPPATH', $app . DIRECTORY_SEPARATOR);
            define('VIEWPATH', $tmp . DIRECTORY_SEPARATOR);
        } else {
            throw new Exception ('[Error] system define path error');
        }

        // set error handler
        set_error_handler('Zy_Common::setErrorHandler');
        set_exception_handler('Zy_Common::setExceptionHandler');
        register_shutdown_function('Zy_Common::shutdownHandler');


        // set charset-related stuff
        $charset = strtoupper(Zy_Config::getConfig('charset'));
        ini_set('default_charset', $charset);

        if (extension_loaded('mbstring')) {
            define('MB_ENABLED', TRUE);
            @ini_set('mbstring.internal_encoding', $charset);
            mb_substitute_character('none');
        } else {
            define('MB_ENABLED', FALSE);
        }

        if (extension_loaded('iconv')) {
            define('ICONV_ENABLED', TRUE);
            @ini_set('iconv.internal_encoding', $charset);
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

        $uri = Zy_Util_URI::getInstance() ;

        $controller = ucfirst($route->controller);
        $action = ucfirst($route->action);

        if ( empty($controller) || empty($action) ) {
            throw new Exception ('[Error] controller or action empty ');
        }

        // init controller object
        $controller = "Controller_" . $controller;
        $objController = new $controller ();

        $objController->init($action);


        if (empty($controller) OR ! file_exists(APPPATH.'controllers/'.$RTR->directory.$controller.'.php'))
        {
            $e404 = TRUE;
        }
        else
        {
            require_once(APPPATH.'controllers/'.$RTR->directory.$controller.'.php');

            if ( !class_exists('Controller_' . $controller, FALSE) )
            {
                $e404 = TRUE;
            }

            $controller = new $contorller_class($action);



            if ( ! class_exists($class, FALSE) OR $method[0] === '_' OR method_exists('CI_Controller', $method))
            {
                $e404 = TRUE;
            }
            elseif (method_exists($class, '_remap'))
            {
                $params = array($method, array_slice($URI->rsegments, 2));
                $method = '_remap';
            }
            elseif ( ! method_exists($class, $method))
            {
                $e404 = TRUE;
            }
            /**
             * DO NOT CHANGE THIS, NOTHING ELSE WORKS!
             *
             * - method_exists() returns true for non-public methods, which passes the previous elseif
             * - is_callable() returns false for PHP 4-style constructors, even if there's a __construct()
             * - method_exists($class, '__construct') won't work because CI_Controller::__construct() is inherited
             * - People will only complain if this doesn't work, even though it is documented that it shouldn't.
             *
             * ReflectionMethod::isConstructor() is the ONLY reliable check,
             * knowing which method will be executed as a constructor.
             */
            elseif ( ! is_callable(array($class, $method)))
            {
                $reflection = new ReflectionMethod($class, $method);
                if ( ! $reflection->isPublic() OR $reflection->isConstructor())
                {
                    $e404 = TRUE;
                }
            }
        }

        if ($e404)
        {
            if ( ! empty($RTR->routes['404_override']))
            {
                if (sscanf($RTR->routes['404_override'], '%[^/]/%s', $error_class, $error_method) !== 2)
                {
                    $error_method = 'index';
                }

                $error_class = ucfirst($error_class);

                if ( ! class_exists($error_class, FALSE))
                {
                    if (file_exists(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php'))
                    {
                        require_once(APPPATH.'controllers/'.$RTR->directory.$error_class.'.php');
                        $e404 = ! class_exists($error_class, FALSE);
                    }
                    // Were we in a directory? If so, check for a global override
                    elseif ( ! empty($RTR->directory) && file_exists(APPPATH.'controllers/'.$error_class.'.php'))
                    {
                        require_once(APPPATH.'controllers/'.$error_class.'.php');
                        if (($e404 = ! class_exists($error_class, FALSE)) === FALSE)
                        {
                            $RTR->directory = '';
                        }
                    }
                }
                else
                {
                    $e404 = FALSE;
                }
            }

            // Did we reset the $e404 flag? If so, set the rsegments, starting from index 1
            if ( ! $e404)
            {
                $class = $error_class;
                $method = $error_method;

                $URI->rsegments = array(
                    1 => $class,
                    2 => $method
                );
            }
            else
            {
                show_404($RTR->directory.$class.'/'.$method);
            }
        }

        if ($method !== '_remap')
        {
            $params = array_slice($URI->rsegments, 2);
        }

    }


    /**
     * Get the Bt singleton
     *
     * @static
     * @return  object
     */
    public static function getInstance () {
        if ( self::$instance === NULL ) {
            self::$instance = self();
        }
        return self::$instance ;
    }
}