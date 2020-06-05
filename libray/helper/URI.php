<?php
class Zy_Helper_URI {


	/**
	 * 解析SERVER中的URI地址, 构造成数组返回
	 *
	 * @return [Array] [URI parse array]
	 */
	public static function getSegmentUri () {
		if ( ! isset($_SERVER['REQUEST_URI'], $_SERVER['SCRIPT_NAME'])) {
			return NULL;
		}

		$uri = parse_url('http://dummy' . $_SERVER['REQUEST_URI']);
		$query = isset($uri['query']) ? $uri['query'] : '';
		$uri = isset($uri['path']) ? $uri['path'] : '';

		if (trim($uri, '/') === '' && strncmp($query, '/', 1) === 0) {
			$query = explode('?', $query, 2);
			$uri = $query[0];
		}

		if ($uri === '/' OR $uri === '') {
			return NULL;
		}

		if (count(explode('/', trim($uri, '/'))) != 3 ){
			return NULL;
		}

		$segments = array();
		// Populate the segments array
		$tok = strtok($uri, '/');
		if ( ! empty($tok) && strlen(trim($tok)) > 0 ) {
			$segments['appname'] = $tok;
		}
		$tok = strtok('/');
		if ( ! empty($tok) && strlen(trim($tok)) > 0 ) {
			$segments['controller'] = $tok;
		}
		$tok = strtok('/');
		if ( ! empty($tok) && strlen(trim($tok)) > 0 ) {
			$segments['action'] = $tok;
		}

		return $segments;
	}
}
