<?php

/**
 *	时间标记类, 增加start, stop, 方便记录函数的起始和结束时间, stop结束会写入 log 文件本次请求
 *
 */

class Zy_Helper_Benchmark {

	public $marker = array();
	public $marker_name = array();

	private static $instance = NULL;

	private function __construct(){}

	public static function get_instance()
	{
		if (self::$instance === NULL)
		{
			self::$instance  =  new Zy_Helper_Benchmark();
		}
		return self::$instance;
	}

	// 开始时间
	public static function start ($name)
	{
		self::get_instance();
		self::$instance->marker_name[] = $name;
		self::$instance->marker[$name.'_start'] = round(microtime(TRUE) * 1000);
	}

	// 结束时间
	public static function stop ($name)
	{
		self::get_instance();
		self::$instance->marker[$name.'_end'] = round(microtime(TRUE) * 1000);
	}

	// 使用时间
	public static function elapsed ($name)
	{
		self::get_instance();
		if (isset(self::$instance->marker[$name.'_start']))
		{
			self::$instance->marker[$name.'_end'] =
				isset(self::$instance->marker[$name.'_end'])
				? self::$instance->marker[$name.'_end']
				: round(microtime(TRUE) * 1000) ;
			return self::$instance->marker[$name.'_end'] - self::$instance->marker[$name.'_start'];
		}
		return FALSE;
	}

	// 统计全部时间
	public static function elapsed_all ()
	{
		self::get_instance();

		// 通过json 统计全部时间
		$arrRes = array();
		foreach (self::$instance->marker_name as $name) {
			if (isset(self::$instance->marker[$name.'_start'])
				&&isset(self::$instance->marker[$name.'_end']))
			{
				$arrRes[$name]	= self::$instance->marker[$name.'_end'] - self::$instance->marker[$name.'_start'];
			}
		}
		return json_encode($arrRes);
	}
}
