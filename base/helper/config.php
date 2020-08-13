<?php
/**
 *  加载系统配置和工程配置日志目录, 并提供相应
 *  系统配置  SYSPATH/config/config.php
 *  工程配置  APPPATH/config/config.php
 *
 */

class Zy_Helper_Config {

	private static $config = array();

    /**
     * 读取配置, 读取成功返回值
     *
     * @param  mixed   键
     * @return mixed   值
     */
	public static function getConfig ($pkg = NULL, $key = NULL)
	{
		if (empty(self::$config)) {
			self::$config = self::load_config ();
		}

		if (empty(self::$config)){
			trigger_error ('[Error] config error [Detail] load configure error');
		}

        if ($key === NULL || $pkg === NULL)
        {
            return NULL;
        }

        return isset(self::$config[$pkg][$key]) ? self::$config[$pkg][$key] : NULL;
	}


    /**
     * 写入配置
     *
     * @param  mixed   键
     * @param  mixed   值
     * @return  bool
     */
	public static function setConfig ($pkg, $key, $value)
	{
		self::$config[$pkg][$key] = $value;
	}


    /**
     * 从配置文件中读取配置选项, 工程配置与系统配置相同则使用系统配置
     *
     * @return void
     */
    private static function load_config ()
    {
        // load system config
        $system_config_path = SYSPATH . 'config/config.php';
        if (file_exists($system_config_path))
        {
            require($system_config_path);
        }

        if (!isset($configure) or empty($configure))
        {
            trigger_error ('Error] config error [Detail] sysconfig not set or empty');
        }

        // load application config
        $project_config_path = BASEPATH . 'config/config.php';
        if (file_exists($project_config_path)) 
        {
            require($project_config_path);
        }

        if (isset($config) && is_array($config))
        {
            foreach ($config as $key => $value)
            {
                if (!isset($configure[$key]))
                {
                    $configure[$key]   = $value;
                }
            }
        }
        return $configure;
    }
}
