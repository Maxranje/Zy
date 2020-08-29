<?php
/**
 * Dao的公共基类，封装了常见的数据库操作
 */

class Zy_Core_Dao{
    /**
     * 待连接数据库配置名称
     * @var string
     */
    protected $_dbName;

    /**
     * 数据库连接对象
     * @var object
     */
    protected $_db;

    /**
     * 结果集对象
     * @var resource
     */
    protected $_res;

    /**
     * SQL构造类
     * @var resource
     */
    protected $_maker;

    //是否每次都获取新的连接，true是，false否，默认false
    protected $_new        = FALSE;


    /**
     * 待连接的数据表名称
     * @var string
     */
    protected $_table;

    /**
     * 程序中的字段名和数据表列名的映射数组
     * @var array
     */
    public $arrFieldsMap;

    /**
     * Dao基类的构造函数，子类需要写自己的构造函数覆盖父类构造函数.
     *
     * 子类构造函数Demo:<br/>
     * <code>
     * public function __construct() {
     *     $this->_dbName = "question";
     *     $this->_table  = "tblQuestion";
     *     $this->arrFieldsMap = array(
     *         'field' => 'column',
     *     );
     * }
     * </code>
     */
    public function __construct() {}

    /**
     * 使用输入的SQL语句进行查询, 可使用绑定参数
     *
     * @api
     * @param  string $sql SQL语句
     * @param  array|false $bind_param  绑定参数, false为不绑定
     * @return array|bool  返回查询结果集，失败为false
     */
    public function query($sql, $bind_param = FALSE) {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }
        $this->_res = $this->_db->query($sql);

        return is_bool($this->_res) ? $this->_res : $this->_res->result();
    }



    /**
     * 获取影响行数
     * @api
     * @param  null
     * @return int|false 返回查询结果集，失败为false
     */
    public function getAffectedRows() {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }
        $nums = $this->_db->affected_rows();
        return $nums < 0 ? FALSE : intval($nums);
    }


    /**
     * Select查询，根据限制条件获取结果数组
     *
     * @param  mixed  $arrConds   限制条件，数组或者字符串形式均可, 示例:<br/>
     * <code>
     * array (
     *     'field' => value,
     *     'field' => array(value1, "<", value2, "?"),
     *     'field < vale'
     * );
     * </code>
     * 或者<br/>
     * <code>"field in (...,...)"</code>
     * <b>注意</b>：字符串格式不会自动做字段映射
     * @param  array  $arrFields  需要查询的字段名数组，格式必须为数组
     * @param  array  $arrOptions SQL前置选项，示例：<br/>
     * <code>
     * $option = array(
     *     'DISTINCT',
     *     'SQL_NO_CACHE'
     * );
     * </code>
     * @param  array $arrAppends  SQL后置选项,示例：<br/>
     * <code>
     * $appends = array(
     *     'ORDER BY b.id',
     *     'LIMIT 5'
     * );
     * </code>
     * @param  array $strIndex    支持mysql的USE/IGNORE/FORCE Index的语法，指定
     * 的索引名称，示例：<br/>
     * <code>$strIndex = "USE INDEX (index1, index2)";</code>
     * 或者<br/>
     * <code>$strIndex = "FORCE INDEX (index1)";</code>
     * @return array|false 返回查询的结果列表，连接DB失败返回false
     *
     * @see getRecordByConds() 获取单条记录
     */
    public function getListByConds($arrConds, $arrFields, $arrOptions = NULL, $arrAppends = NULL, $strIndex = NULL) {
        if (empty($this->_db)) {
             $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }
        //限制条件字段以及格式的转换
        $arrConds  = Zy_Database_Dbservice::mapRow($arrConds, $this->arrFieldsMap);
        $arrConds  = Zy_Database_Dbservice::getConds($arrConds);

        //查询字段的转换
        $arrFields = Zy_Database_Dbservice::mapField($arrFields, $this->arrFieldsMap, true);
        //表名以及强制索引字段的添加
        $tableName = (empty($strIndex)) ? $this->_table : $this->_table." {$strIndex}";
        $querySql = Zy_Database_Dbsqlmaker::getSelect ($this->_db, $tableName, $arrFields,$arrConds, $arrOptions, $arrAppends);
        $this->_res = $this->_db->query($querySql);
        if ($this->_res === false){
            return FALSE;
        }
        return $this->_res->result();
    }

    public function getRecordByConds($arrConds, $arrFields, $arrOptions = NULL, $arrAppends = NULL, $strIndex = NULL) {
        if (empty($this->_db)) {
             $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }

        $arrRes = $this->getListByConds($arrConds, $arrFields, $arrOptions, $arrAppends, $strIndex);
        if (false === $arrRes) {
            return false;
        }
        return !empty($arrRes[0]) ? $arrRes[0] : array();
    }

    /**
     * Insert插入，不支持多行插入
     *
     * @param  array $arrFields 需要插入的键值数组，示例：<br/>
     * <code>
     * array (
     *     'name' => 'Robin Li',
     * )
     * </code>
     * @return bool 插入成功返回true，否则返回false
     */
    public function insertRecords($arrFields) {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }

        $arrFields = Zy_Database_Dbservice::mapRow($arrFields, $this->arrFieldsMap);
        $querySql = Zy_Database_Dbsqlmaker::getInsert ($this->_db, $this->_table, $arrFields);

        $this->_res = $this->_db->query($querySql);
        return $this->_res === false ? false : true;
    }

    /**
     * 返回插入后的自增ID
     *
     * @return int 最新的自增ID
     */
    public function getInsertId() {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }
        return $this->_db->insert_id();
    }

    /**
     * 返回上次执行的语句
     *
     * @return string
     */
    public function getLastSQL() {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName, $this->_new, $this->_logFile, $this->_autoRotate);
        }
        return $this->_db->last_sql();
    }

    /**
     * Update更新，根据限制条件更新对应的数据库记录
     *
     * @param  mixed  $arrConds   限制条件，数组或者字符串形式均可，示例见{@link getListByConds()}的conds参数
     * @param  array  $arrFields  需要更新的字段名数组，格式必须为数组且包含主键
     * @param  array  $arrOptions SQL前置选项，参见{@link getListByConds()}对应参数
     * @param  array  $arrAppends SQL后置选项，参见{@link getListByConds()}对应参数
     * @return bool 成功返回true，失败返回false
     */
    public function updateByConds($arrConds, $arrFields, $arrOptions=NULL, $arrAppends=NULL) {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }

        $arrConds  = Zy_Database_Dbservice::mapRow($arrConds, $this->arrFieldsMap);
        $arrConds  = Zy_Database_Dbservice::getConds($arrConds);
        $arrFields = Zy_Database_Dbservice::mapRow($arrFields, $this->arrFieldsMap);

        $querySql = Zy_Database_Dbsqlmaker::getUpdate ($this->_db, $this->_table, $arrFields, $arrConds, $arrOptions, $arrAppends);
        $this->_res = $this->_db->query($querySql);
        return $this->_res === false ? false : true;
    }

    /**
     * Delete删除，根据限制条件删除对应的数据库记录
     *
     * @param  mixed  $arrConds   限制条件，数组或者字符串形式均可，示例见{@link getListByConds()}的conds参数
     * @return bool 成功返回true，失败返回false
     */
    public function deleteByConds($arrConds) {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }

        $arrConds = Zy_Database_Dbservice::mapRow($arrConds, $this->arrFieldsMap);
        $arrConds = Zy_Database_Dbservice::getConds($arrConds);

        $querySql = Zy_Database_Dbsqlmaker::getDelete ($this->_db, $this->_table, $arrConds, NULL);
        $this->_res = $this->_db->query($querySql);
        return $this->_res === false ? false : true;
    }

    /**
     * Count符合条件的记录数
     *
     * @param  mixed  $arrConds   限制条件，数组或者字符串形式均可，示例见getListByConds
     * @return int|false 成功返回记录总数，失败返回false
     */
    public function getCntByConds($arrConds) {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }
        $arrConds = Zy_Database_Dbservice::mapRow($arrConds, $this->arrFieldsMap);
        $arrConds = Zy_Database_Dbservice::getConds($arrConds);

        $querySql = Zy_Database_Dbsqlmaker::getSelect ($this->_db, $this->_table, array('count(*) as count') , $arrConds);
        $this->_res = $this->_db->query($querySql);
        if ($this->_res === false){
            return FALSE;
        }

        $count = $this->_res->result();
        return intval($count[0]['count']);
    }

    /**
     * 关闭DAO对应的DB连接，适用于部分场景需要迅速断开DB连接的情况
     *
     * @return bool 成功返回true，否则false
     */
    public function closeDB() {
        if (!empty($this->_db)) {
            return $this->_db->close();
        }
        return true;
    }

    /**
     * 重新建立在先前断开的连接
     *
     * @return bool 成功返回true，否则false
     */
    public function reconnect() {
        if (!empty($this->_db) && !$this->_db->isConnected()) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName, true, $this->_logFile);
        }
        return $this->_db->reconnect();
    }

    /**
     * 设置或查询当前自动提交状态
     *
     * @param  bool $bolAuto  NULL返回当前状态，其它设置当前状态
     * @return bool 查询时，成功返回bool值，失败返回null值，设置时，返回bool值
     */
    public function autoCommit($bolAuto) {
        if (!empty($this->_db)) {
            return $this->_db->auto_commit($bolAuto);
        }
        return false;
    }

    /**
     * 开始一个事务
     *
     * @return bool 成功返回true，否则false
     */
    public function startTransaction() {
        if (empty($this->_db)) {
            $this->_db = Zy_Database_Dbservice::getDB($this->_dbName);
        }
        if (!empty($this->_db)) {
            return $this->_db->trans_start();
        }
        return false;
    }

    /**
     * 提交一个事务
     *
     * @return bool 成功返回true，否则false
     */
    public function commit() {
        if (!empty($this->_db)) {
            return $this->_db->commit();
        }
        return false;
    }

    /**
     * 回滚当前事务
     *
     * @return bool 成功返回true，否则false
     */
    public function rollback() {
        if (!empty($this->_db)) {
            return $this->_db->rollback();
        }
        return false;
    }
}
