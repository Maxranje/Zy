<?php

class Service_Teacher_Lists {

    private $daoTeacher ;

    public function __construct() {
        $this->daoTeacher = new Dao_Teacher_Mysql_Teacher () ;
    }

    public function getTeacherList ($pn = 0, $rn = 40){
        $arrConds = array(
            'status' => 1,
        );

        $arrFields = $this->daoTeacher->simpleFields;

        $arrOptions = array(
            'order by id asc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoTeacher->getListByConds($arrConds, $arrFields, $arrOptions);
        if (empty($lists)) {
            return [];
        }
        return array_values($lists);
    }

    public function getTeacherDetails ($teacherid){
        if (empty($teacherid)) {
            return false;
        }

        $arrConds = array(
            'status' => 1,
        );

        $arrFields = $this->daoTeacher->arrFieldsMap;

        $teacherinfo = $this->daoTeacher->getRecordByConds($arrConds, $arrFields);

        return empty($teacherinfo) ? false : $teacherinfo;
    }
}