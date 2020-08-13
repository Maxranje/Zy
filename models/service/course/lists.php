<?php

class Service_Course_Lists {

    private $daoCourse ;

    private $daoTeacherCourse ;

    const COURSE_TYPE_LISTS = [
        ['id'    => 's', 'name'  => '',],
        ['id'    => '','name'  => '',],
        ['id'    => '','name'  => '',],
        ['id'    => 's', 'name'  => '',],
        ['id'    => '','name'  => '',],
        ['id'    => '','name'  => '',],
    ];

    public function __construct() {
        $this->daoCourse = new Dao_Course_Mysql_Course () ;
        $this->daoTeacherCourse = new Dao_Teacher_Mysql_Course();
    }

    public function getCourseList ($courseType, $teacherid, $isrecommend = 0, $pn = 0, $rn = 20) {
        if (!empty($courseType)) {
            $courseList = $this->getCourseListByType ($courseType, $pn, $rn) ;
        } else if (!empty($teacherid)) {
            $courseList = $this->getCourseListByTeacher ($courseType, $pn, $rn) ;
        } else {
            $courseList = $this->getCourseListByRecommend ($isrecommend, $pn, $rn);
        }

        foreach ($courseList as $index => $course) {
            $course = date('Y年m月d日', $course['createtime']);
            $courseList[$index] = $course;
        }

        return $courseList;
    }

    public function getCourseListByType ($courseType, $pn = 0, $rn = 20) {
        
        if (empty($courseType)) {
            return [];
        }

        $arrConds = [
            'status' => 1,
            'coursetype' => $courseType,
        ];

        $arrFields = $this->daoCourse->simpleFields;

        $arrOptions = array(
            'order by id desc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoBanner->getListByConds($arrConds, $arrFields, $arrOptions);
        if (empty($lists)) {
            return [];
        }
        return array_values($lists);
    }

    public function getCourseListByTeacher ($teacherid, $pn = 0, $rn = 20) {
        if (empty($teacherid)) {
            return [];
        }

        $arrConds = [
            'status' => 1,
            'teacherid' => $teacherid,
        ];

        $arrOptions = array(
            'order by id desc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoTeacherCourse->getListByConds($arrConds, $this->daoTeacherCourse->simpleFields, $arrOptions);
        if (empty($lists)) {
            return [];
        }

        $courseIds = array_column($lists, 'courseid');
        $arrConds = [
            'status = 1',
            "courseid in (" . implode(",", $courseIds) . ")",
        ];
        $lists = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields, NULL, $arrOptions);
        if (empty($lists)) {
            return [];
        }

        return array_values($lists);
    }

    public function getCourseListByRecommend ($isrecommend = 0, $pn = 0, $rn = 20) {
        $arrConds = [
            'status' => 1,
        ];

        $arrOptions = array(
            "limit {$pn} , {$rn}",
        );

        if ($isrecommend == 1) {
            $arrOptions[] =  'order by id desc';
        } else {
            $arrOptions[] =  'order by recommend desc, id desc';
        }

        $lists = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields, $arrOptions);
        if (empty($lists)) {
            return [];
        }
        
        return array_values($lists);
    }
}