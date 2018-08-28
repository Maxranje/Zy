<?php
/**
 *  工程输出类
 */
class Zy_Output {

	// 最终输出内容
	public $final_output;

	// 默认缓存时间
	public $cache_expiration = 0;

	// 消息头数组
	public $headers = array();

	// 消息头中 Content-Type
	public $mimes =	array();

	// 默认Content-Type
	protected $mime_type = 'text/html';

	// php.ini zlib.output_compression 状态标记
	protected $_zlib_oc = FALSE;

	// 输出压缩标记
	protected $_compress_output = FALSE;

	// mbstring 函数覆盖
	protected static $func_overload;

	public function __construct()
	{
		$this->_zlib_oc = (bool) ini_get('zlib.output_compression');
		$this->_compress_output = (
			$this->_zlib_oc === FALSE
			&& Zy_Config::getConfig('compress_output') === TRUE
			&& extension_loaded('zlib')
		);

		isset(self::$func_overload) OR self::$func_overload = (extension_loaded('mbstring') && ini_get('mbstring.func_overload'));

		// Get mime types for later
		$this->mimes = Zy_Common::get_mimes();
	}

	public function get_output()
	{
		return $this->final_output;
	}

	public function set_output($output)
	{
		$this->final_output = $output;
		return $this;
	}

	public function append_output($output)
	{
		$this->final_output .= $output;
		return $this;
	}


	// 设置响应消息头
	public function set_header($header, $replace = TRUE)
	{
		// set_header('HTTP/1.0 200 OK');
		// set_header('HTTP/1.1 200 OK');
		// set_header('Last-Modified: '.gmdate('D, d M Y H:i:s', $last_update).' GMT');
		// set_header('Cache-Control: no-store, no-cache, must-revalidate');
		// set_header('Cache-Control: post-check=0, pre-check=0');
		// set_header('Pragma: no-cache');
		if ($this->_zlib_oc && strncasecmp($header, 'content-length', 14) === 0)
		{
			return $this;
		}

		$this->headers[] = array($header, $replace);
		return $this;
	}

	// 设置Content_Type响应头
	public function set_content_type($mime_type, $charset = NULL)
	{
		if (strpos($mime_type, '/') === FALSE)
		{
			if (isset($this->mimes[$extension]))
			{
				$mime_type = $this->mimes[$extension];

				if (is_array($mime_type))
				{
					$mime_type = current($mime_type);
				}
			}
		}

		$this->mime_type = $mime_type;

		if (empty($charset))
		{
			$charset = Zy_Config::getConfig('charset');
		}

		$header = 'Content-Type: '.$mime_type.(empty($charset) ? '' : '; charset='.$charset);

		$this->headers[] = array($header, TRUE);
		return $this;
	}


	// 返回当前消息头中的Content-Type 如果为空,  则返回默认的
	public function get_content_type()
	{
		for ($i = 0, $c = count($this->headers); $i < $c; $i++)
		{
			if (sscanf($this->headers[$i][0], 'Content-Type: %[^;]', $content_type) === 1)
			{
				return $content_type;
			}
		}

		return 'text/html';
	}


	// 返回当前消息头
	public function get_header($header)
	{
		// Combine headers already sent with our batched headers
		$headers = array_merge(
			// We only need [x][0] from our multi-dimensional array
			array_map('array_shift', $this->headers),
			headers_list()
		);

		if (empty($headers) OR empty($header))
		{
			return NULL;
		}

		// Count backwards, in order to get the last matching header
		for ($c = count($headers) - 1; $c > -1; $c--)
		{
			if (strncasecmp($header, $headers[$c], $l = self::strlen($header)) === 0)
			{
				return trim(self::substr($headers[$c], $l+1));
			}
		}

		return NULL;
	}


	// 手工设置服务器的 HTTP 状态码
	public function set_status_header($code = 200, $text = '')
	{
		Zy_Common::set_status_header($code, $text);
		return $this;
	}


	//  程序分析器
	public function enable_profiler($val = TRUE)
	{
		$this->enable_profiler = is_bool($val) ? $val : TRUE;
		return $this;
	}

	//

	/**
	 * Set Profiler Sections
	 *
	 * Allows override of default/config settings for
	 * Profiler section display.
	 *
	 * @param	array	$sections	Profiler sections
	 * @return	CI_Output
	 */
	public function set_profiler_sections($sections)
	{
		if (isset($sections['query_toggle_count']))
		{
			$this->_profiler_sections['query_toggle_count'] = (int) $sections['query_toggle_count'];
			unset($sections['query_toggle_count']);
		}

		foreach ($sections as $section => $enable)
		{
			$this->_profiler_sections[$section] = ($enable !== FALSE);
		}

		return $this;
	}



	// 设置缓存时间, 以秒为单位
	public function cache($time)
	{
		$this->cache_expiration = is_numeric($time) ? $time : 0;
		return $this;
	}

	//

	/**
	 * Display Output
	 *
	 * @uses	Zy_Output::$final_output
	 * @param	string	$output	Output data override
	 * @return	void
	 */
	public function display($output = '')
	{
		if ($output === '')
		{
			$output = $this->final_output;
		}


		if ($this->cache_expiration > 0)
		{
			$this->_write_cache($output);
		}


		Zy_Benchmark::stop('ts_all');

		// 设置压缩格式
		if ($this->_compress_output === TRUE
			&& isset($_SERVER['HTTP_ACCEPT_ENCODING'])
			&& strpos($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') !== FALSE)
		{
			ob_start('ob_gzhandler');
		}
		else
		{
			ob_start();
		}

		if (count($this->headers) > 0)
		{
			foreach ($this->headers as $header)
			{
				@header($header[0], $header[1]);
			}
		}

		Zy_Log::addnotice("time: [" . Zy_Benchmark::elapsed_all() . "] request and response done" );
		echo $output;
		ob_get_clean();
		exit;
	}

	protected static function strlen($str)
	{
		return (self::$func_overload)
			? mb_strlen($str, '8bit')
			: strlen($str);
	}

	protected static function substr($str, $start, $length = NULL)
	{
		if (self::$func_overload)
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
