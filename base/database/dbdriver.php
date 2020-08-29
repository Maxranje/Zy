<?php

abstract class Zy_Database_Dbdriver {

	// DNS
	public $dsn;

	// 数据库的用户名
	public $username;

	// 数据库的密码
	public $password;

	// 数据库HOST地址
	public $hostname;

	// 库名
	public $database;

	// 驱动组件
	public $dbdriver		= 'mysqli';

	// PDO驱动组件
	public $subdriver;

	// 字符集
	public $char_set		= 'utf8';

	// 连接字符集
	public $dbcollat		= 'utf8_general_ci';

	// 是否使用加密连接
	public $encrypt			= FALSE;

	// 数据库端口
	public $hostport		= 3306;

	// 是否使用持续连接
	public $pconnect		= FALSE;

	// 连接线程ID
	public $conn_id			= NULL;

	// 结果
	public $result_id		= FALSE;


	// 查询次数
	public $query_count		= 0;

	//
	public $bind_marker		= '?';

	// 保存查询语句
	public $save_queries		= TRUE;

	// 查询sql的数组
	public $queries			= array();

	// 查询时间数组
	public $query_times		= array();

	// 事务状态
	public $trans_enabled		= TRUE;

	// SQL构造器
	public $sql_assember		= NULL;

	// 连接的超时时间
	public $timeout 			= 10;


	/**
	 * 初始化连接变量
	 */
	public function __construct($params)
	{
		if (is_array($params))
		{
			foreach ($params as $key => $val)
			{
				$this->$key = $val;
			}
		}
	}

	/**
	 * 初始化数据库设置
	 *
	 */
	public function initialize()
	{
		if ($this->conn_id)
		{
			return TRUE;
		}

		$this->conn_id = $this->db_connect($this->pconnect);

		if ( ! $this->conn_id)
		{
			// Check if there is a failover set
			if ( ! empty($this->failover) && is_array($this->failover))
			{
				// Go over all the failovers
				foreach ($this->failover as $failover)
				{
					// Replace the current settings with those of the failover
					foreach ($failover as $key => $val)
					{
						$this->$key = $val;
					}

					// Try to connect
					$this->conn_id = $this->db_connect($this->pconnect);

					// If a connection is made break the foreach loop
					if ($this->conn_id)
					{
						break;
					}
				}
			}

			// We still don't have a connection?
			if ( ! $this->conn_id)
			{
				trigger_error ('[Error] database connect [Detail] conn_id false');
			}
		}

		return $this->db_set_charset($this->char_set);
	}

	// DB连接
	abstract public function db_connect();
	// 重连
	abstract public function reconnect() ;
	// 选择数据库
	abstract public function db_select($database);
	// 错误信息
	abstract public function error() ;
	// 字符集设置
	abstract public function db_set_charset ($charset);


	// 禁用事务
	public function trans_off()
	{
		$this->trans_enabled = FALSE;
	}

	// 自动提交
	public function auto_commit($autoCommit = TRUE)
	{
		$this->_auto_commit($autoCommit);
	}

	// 启动事务
	public function trans_start()
	{
		if ( ! $this->trans_enabled)
		{
			return FALSE;
		}

		return $this->_trans_start();
	}

	// 事务提交
	public function commit()
	{
		if ( ! $this->trans_enabled)
		{
			return FALSE;
		}
		return $this->_commit();
	}

	// 事务回滚
	public function rollback()
	{
		if ( ! $this->trans_enabled)
		{
			return FALSE;
		}
		return $this->_rollback();
	}

	// 返回最后一次SQL语句
	public function last_sql()
	{
		return end($this->queries);
	}

	// 关闭连接
	public function close()
	{
		if ($this->conn_id)
		{
			$this->_close();
			$this->conn_id = FALSE;
		}
	}


	// 执行sql
	public function simple_query($sql)
	{
		if ( ! $this->conn_id)
		{
			if ( ! $this->initialize())
			{
				return FALSE;
			}
		}

		return $this->_execute($sql);
	}

	/**
	 * 查询功能, 支持单条sql 和  需要绑定参数的SQL
	 *
	 * @param	string	$sql
	 * @return	mixed   DML语句返回TRUE OR FALSE, DQL返回结果集对象, 失败返回FALSE
	 */
	public function query($sql = '', $binds = FALSE)
	{
		if (empty($sql))
		{
			trigger_error('[Error] db invalid query [Detail] sql empty');
		}
		$_sql_type = $this->sql_type($sql);

		if ( $_sql_type != 'dml' && $_sql_type != 'dql' ){
			Zy_Helper_Log::warning('Illegal operation SQL type');
			return FALSE;
		}

		if ($binds !== FALSE && is_array($binds))
		{
			$sql = $this->compile_binds($sql, $binds);
		}

		// 记录本次查询的SQL
		if ($this->save_queries === TRUE)
		{
			$this->queries[] = $sql;
		}

		Zy_Helper_Benchmark::start('db_query');
		if (FALSE === ($this->result_id = $this->simple_query($sql)))
		{
			$error = $this->error();
			Zy_Helper_Log::warning('db query error: '.$error['message'].' - Invalid query: '.$sql);

			return FALSE;
		}
		Zy_Helper_Benchmark::stop('db_query');

		// 记录本次查询时间
		if ($this->save_queries === TRUE)
		{
			$this->query_times[] = Zy_Helper_Benchmark::elapsed('db_query');
		}

		// DML语句直接返回
		if ($_sql_type == 'DML')
		{
			return TRUE;
		}

		// 加载结果集对象
		$driver		= 'Zy_Database_Drivers_'.$this->dbdriver.'_Result';
		$result		= new $driver($this);

		return $result;
	}

	/**
	 * 绑定参数, 对参数进行字符正则处理
	 *
	 * @param	string	the sql statement
	 * @param	array	an array of bind data
	 * @return	string
	 */
	public function compile_binds($sql, $binds)
	{
		if (empty($this->bind_marker) OR strpos($sql, $this->bind_marker) === FALSE)
		{
			return $sql;
		}
		elseif ( ! is_array($binds))
		{
			$binds = array($binds);
			$bind_count = 1;
		}
		else
		{
			// Make sure we're using numeric keys
			$binds = array_values($binds);
			$bind_count = count($binds);
		}

		// We'll need the marker length later
		$ml = strlen($this->bind_marker);

		// Make sure not to replace a chunk inside a string that happens to match the bind marker
		if ($c = preg_match_all("/'[^']*'|\"[^\"]*\"/i", $sql, $matches))
		{
			$c = preg_match_all('/'.preg_quote($this->bind_marker, '/').'/i',
				str_replace($matches[0],
					str_replace($this->bind_marker, str_repeat(' ', $ml), $matches[0]),
					$sql, $c),
				$matches, PREG_OFFSET_CAPTURE);

			// Bind values' count must match the count of markers in the query
			if ($bind_count !== $c)
			{
				return $sql;
			}
		}
		elseif (($c = preg_match_all('/'.preg_quote($this->bind_marker, '/').'/i', $sql, $matches, PREG_OFFSET_CAPTURE)) !== $bind_count)
		{
			return $sql;
		}

		do
		{
			$c--;
			$escaped_value = $this->_escape_str($binds[$c]);
			if (is_array($escaped_value))
			{
				$escaped_value = '('.implode(',', $escaped_value).')';
			}
			$sql = substr_replace($sql, $escaped_value, $matches[0][$c][1], $ml);
		}
		while ($c !== 0);

		return $sql;
	}

	public function escape_str ($str) {
		return $this->_escape_str ($str);
	}

	// 判断sql类型
	public function sql_type($sql)
	{
		if ((bool) preg_match('/^\s*"?(SET|INSERT|UPDATE|DELETE)\s/i', $sql)){
			return 'dml';
		} else if ((bool) preg_match('/^\s*"?(SELECT)\s/i', $sql)) {
			return 'dql';
		} else if ((bool) preg_match('/^\s*"?(CREATE|DROP|TRUNCATE|LOAD|COPY|ALTER|RENAME)\s/i', $sql)) {
			return 'ddl';
		} else  if ((bool) preg_match('/^\s*"?(GRANT|REVOKE|LOCK|UNLOCK|REINDEX|MERGE)\s/i', $sql)) {
			return 'dcl';
		}
		return FALSE;
	}
}
