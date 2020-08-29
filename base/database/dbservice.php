<?php
/**
 * 数据库连接工具, 根据DAO层请求获取不同的配置文件, 初始化数据连接代码
 * v 1.0.0 版本, 根据conf和配置连接不同数据库
 *
 */

class Zy_Database_Dbservice
{
    /**
     * DB配置信息
     */
	private static $db_config =  NULL;

    /**
     * DB驱动程序
     */
    private static $db_driver =  NULL;

    /**
     * 承载DB驱动数组, 无需多次申请
     */
    private static $db_driver_pool = array();


    // 获取DB驱动程序, 可能一个程序中会连接两个不同的数据库,  所以需要根据_dbname重新指定
	public static function getDB ($dbname = 'default') {
        if ( !empty(self::$db_driver_pool[$dbname]) ) { 
            return self::$db_driver_pool[$dbname];
        }
        $db_driver_name = Zy_Helper_Config::getConfig('system', 'dbdriver');
        if (empty($db_driver_name)) {
            throw new Exception ('[Error] connect db failed, [Detail] db driver read failed');
        }

        self::$db_config = Zy_Helper_Config::getConfig('database', $db_driver_name);
        if (empty(self::$db_config)) {
            throw new Exception ('[Error] connect db failed, [Detail] db config read failed');
        }

		$driver_class = 'Zy_Database_Drivers_'.ucfirst($db_driver_name).'_Driver';
		self::$db_driver = new $driver_class(self::$db_config);
		self::$db_driver->initialize();

        // 如果更换数据库, 需要重新指定
        if ($dbname != self::$db_driver->database) {
            $status = self::$db_driver->db_select($dbname);
            if ($status === FALSE) {
                throw new Exception ('[Error] connect db failed, [Detail] change database failed');
            }
        }
        
        self::$db_driver_pool[$dbname] = self::$db_driver;
		return self::$db_driver;
	}


    /**
     * 与DAO层中配置的MAP进行对比, 保留存在内容, 默认认为MAP中KEY与数据库同步
     */
    public static function mapRow($arrRow, $arrFieldsMap)
    {
    	if (empty($arrRow) || !is_array($arrRow) || empty($arrFieldsMap))
        {
            return $arrRow;
        }
        $ret = array();
        foreach($arrRow as $field => $value)
        {
            if (isset($arrFieldsMap[$field]) && $field != $arrFieldsMap[$field])
            {
                $ret[$arrFieldsMap[$field]] = $value;
                unset($arrRow[$field]);
            }
            else
            {
                $ret[$field] = $value;
            }
        }
        return $ret;
    }

	/**
	 * 对传入的SQL限制条件进行定位成字符串形式, 不接受数组传递
	 */
	public static function getConds($arrConds){
        //参数检查, 对字符串类型直接返回，对于非数组类型返回NULL
        if (is_string($arrConds)) {
        	return $arrConds;
        }
        if (!is_array($arrConds)) {
        	return NULL;
        }

        //对数组类型的进行重新拼装
        $arrCondsRes = array();
        foreach($arrConds as $key => $value){
            if (is_numeric($key) && is_string($value)) {
                $arrCondsRes[] = $value;
            }
            else {
                $arrCondsRes["$key ="] = $value;
            }
        }
        return $arrCondsRes;
    }

    /**
     * 将字段数组和数据库表的列名做映射转换
     * @param  mixed   $arrFields     程序中使用的字段数组，格式为array(field1, field2, ...)，
     *                                若该参数为字符串则直接返回
     * @param  array   $arrFieldsMap  字段和数据库表列名的映射数组
     * @param  boolean $useAs         是否转换成"列名 as 程序字段名"的形式，默认为false不转
     * @return array 转换之后的数组
     */
    public static function mapField($arrFields, $arrFieldsMap, $useAs=false) {
        //参数的校验
        if (empty($arrFields) || !is_array($arrFields) || empty($arrFieldsMap)) {
            return $arrFields;
        }

        //将fields中的名称转换成映射表中对应的名称，如果使用as，则写成 column as field 的形式
        $ret = array();
        foreach($arrFields as $field){
            if (isset($arrFieldsMap[$field])){
                $ret[] = ($useAs != false) ? ($arrFieldsMap[$field]." as ".$field) : $arrFieldsMap[$field];
            } else{
                $ret[] = $field;
            }
        }
        return $ret;
    }
}
