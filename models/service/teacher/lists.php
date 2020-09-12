<?php

class Service_Teacher_Lists {

    private $daoTeacher ;

    const TEACHER_STATUS_ONLINE  = 1;
    const TEACHER_STATUS_OFFLINE = 2;

    public function __construct() {
        $this->daoTeacher = new Dao_Teacher_Mysql_Teacher () ;
    }

    public function getTeacherList ($pn = 0, $rn = 40){
        $arrConds = array(
            'status' => self::TEACHER_STATUS_ONLINE,
        );

        $arrFields = $this->daoTeacher->arrFieldsMap;

        $arrAppends = array(
            'order by teacherid asc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoTeacher->getListByConds($arrConds, $arrFields, null, $arrAppends);
        if (empty($lists)) {
            return [];
        }
        return array_values($lists);
    }
}