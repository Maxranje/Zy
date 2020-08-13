<?php

class Service_Teacher_Course {

    private $daoTeacherCourse ;
    private $daoTeacher;

    public function __construct() {
        $this->daoTeacherCourse = new Dao_Teacher_Mysql_Course () ;
        $this->daoTeacher = new Dao_Teacher_Mysql_Teacher();
    }

    public function getTeacherByCourse ($courseid){
        if (empty($courseid)) {
            return [];
        }

        $arrConds = array(
            'courseid' => $courseid,
        );

        $arrFields = $this->daoTeacherCourse->simpleFields;

        $lists = $this->daoTeacherCourse->getListByConds($arrConds, $arrFields);
        if (empty($lists)) {
            return [];
        }

        $teacherids = array_column($lists, 'teacherid');
        $arrConds = [
            'status = 1',
            "teacherid in (" . implode(",", $teacherids) . ")",
        ];
        $lists = $this->daoTeacher->getListByConds($arrConds, $this->daoTeacher->simpleFields);
        return array_values($lists);
    }
}