<?php
class Zy_Database_Drivers_Mysqli_Driver extends Zy_Database_Dbdriver {

	// 本类DB驱动
	public $dbdriver = 'mysqli';

	// 压缩标志位
	public $compress = FALSE;

	// mysql 的严格模式
	public $stricton;

	// 表示转移符
	protected $_escape_char = '`';

	// mysqli 对象
	protected $_mysqli;


	/**
	 * 数据库连接
	 *
	 * @param	bool	是否使用持久化连接
	 * @return	object
	 */
	public function db_connect($persistent = FALSE) {

		$client_flags = ($this->compress === TRUE) ? MYSQLI_CLIENT_COMPRESS : 0;

		$this->_mysqli = mysqli_init();

		$this->_mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, $this->timeout);

		if (isset($this->stricton))
		{
			if ($this->stricton)
			{
				$this->_mysqli->options(MYSQLI_INIT_COMMAND, 'SET SESSION sql_mode = CONCAT(@@sql_mode, ",", "STRICT_ALL_TABLES")');
			}
			else
			{
				$this->_mysqli->options(MYSQLI_INIT_COMMAND,
					'SET SESSION sql_mode =
					REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(
					@@sql_mode,
					"STRICT_ALL_TABLES,", ""),
					",STRICT_ALL_TABLES", ""),
					"STRICT_ALL_TABLES", ""),
					"STRICT_TRANS_TABLES,", ""),
					",STRICT_TRANS_TABLES", ""),
					"STRICT_TRANS_TABLES", "")'
				);
			}
		}

		if ($this->_mysqli->real_connect($this->hostname, $this->username, $this->password, $this->database, $this->hostport, '', $client_flags)){
			// Prior to version 5.7.3, MySQL silently downgrades to an unencrypted connection if SSL setup fails
			if (
				($client_flags & MYSQLI_CLIENT_SSL)
				&& version_compare($this->_mysqli->client_info, '5.7.3', '<=')
				&& empty($this->_mysqli->query("SHOW STATUS LIKE 'ssl_cipher'")->fetch_object()->Value)
			)
			{
				$this->_mysqli->close();
				trigger_error('MySQLi was configured for an SSL connection, but got an unencrypted connection instead!');
			}

			return $this->_mysqli;
		}

		return FALSE;
	}



	// 连接状态标记
	public function reconnect()
	{
		if ($this->conn_id !== FALSE && $this->conn_id->ping() === FALSE)
		{
			$this->conn_id = FALSE;
		}
	}


	// 选择数据库
	public function db_select($database)
	{
		if ($database === '')
		{
			return FALSE;
		}

		if ($this->conn_id->select_db($database))
		{
			$this->database = $database;
			return TRUE;
		}

		return FALSE;
	}


	// 设置默认字符集
	public function db_set_charset($charset)
	{
		return $this->conn_id->set_charset($charset);
	}


	/**
	 * 执行查询
	 *
	 * @param	string	$sql	an SQL query
	 * @return	mixed
	 */
	protected function _execute($sql)
	{
		return $this->conn_id->query($sql);
	}

	// 默认提交
	protected function _auto_commit ($autoCommit) {
		$this->conn_id->autocommit($autoCommit);
	}

	// 开启事务
	protected function _trans_start()
	{
		$this->conn_id->autocommit(FALSE);
		$this->conn_id->begin_transaction();
	}


	// 事务提交
	protected function _commit()
	{
		if ($this->conn_id->commit())
		{
			$this->conn_id->autocommit(TRUE);
			return TRUE;
		}

		return FALSE;
	}



	// 事务回滚
	protected function _rollback()
	{
		if ($this->conn_id->rollback())
		{
			$this->conn_id->autocommit(TRUE);
			return TRUE;
		}

		return FALSE;
	}


	// 转义 SQL 语句中使用的字符串中的特殊字符，并考虑到连接的当前字符集
	protected function _escape_str($str)
	{
		return $this->conn_id->real_escape_string($str);
	}


	// 返回前一次执行语句的影响行数
	public function affected_rows()
	{
		return $this->conn_id->affected_rows;
	}


	// 返回上一条插入语句的ID值
	public function insert_id()
	{
		return $this->conn_id->insert_id;
	}


	// 返回数据库连接或执行的错误状态信息
	public function error()
	{
		if ( ! empty($this->_mysqli->connect_errno))
		{
			return array(
				'code'    => $this->_mysqli->connect_errno,
				'message' => $this->_mysqli->connect_error
			);
		}

		return array('code' => $this->conn_id->errno, 'message' => $this->conn_id->error);
	}


	// 关闭数据库连接
	protected function _close()
	{
		$this->conn_id->close();
	}

}
