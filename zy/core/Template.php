<?php
/**
 * TWIG 模板引擎外部调用入口, 封装了TWIG引擎的调用
 *
 */
class Zy_Template {

    /**
     * 模板引擎使用的输出参数
     * @var array
     */
    protected $arrOutput        = array();

    /**
     * 模板引擎环境
     * @var object
     */
    protected $twigloader;

    /**
     * 模板引擎引用
     * @var mixed
     */
    protected $twig;

    /**
     * 系统参数
     * @var null
     */
    private $_resource = NULL;

    private static $instance = NULL;

    private function __construct() {
        $this->twigloader = new Twig_Loader_Filesystem(VIEWPATH);
        $this->twig = new Twig_Environment($this->twigloader, array('debug' => true));
        $this->twig->addExtension(new Twig_Extension_Debug());
        $this->_resource = $this->getPublicParam();
        $this->arrOutput['resource']  = $this->_resource;
    }

    public static function getInstance ($options = array()) {
        if (self::$instance === NULL) {
            self::$instance = new Zy_Template ($options);
        }
        return self::$instance;
    }

    /**
     * 模板中传递的参数,  只接受数组
     *
     * @param  array  $param        [参数数组]
     * @return
     */
    public function assgin ($param = array()) {
        if (is_array($param) && !empty($param)) {
            foreach ($param as $key => $item) {
                if (!is_numeric($key)) {
                    $this->arrOutput[$key]    = $item;
                }
            }
        }
        return $this;
    }

    /**
     * 加载模板, 根据给出的参数和APP_NAME组成模板路径, 输出结果
     *
     * @param
     * @return [type]               [description]
     */
    public function display ($twigPath = '')
    {
        if (empty($twigPath) || !file_exists(VIEWPATH . $twigPath))
        {
            trigger_error('template file not finde "'. $twigPath .'"');
        }
        $this->twig->display($twigPath,$this->arrOutput);
        exit(0);
    }

    /**
     * 系统公共响应参数
     *
     * @return array
     */
    private function getPublicParam () {
        return array(
            'base_url'      => Zy_Config::getConfig('base_url'),
        );
    }
}