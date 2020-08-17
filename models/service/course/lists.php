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

    const COURSE_STATUS_NORMAL = 1;
    const COURSE_STATUS_OFLINE = 0;

    public function __construct() {
        $this->daoCourse = new Dao_Course_Mysql_Course () ;
        $this->daoTeacherCourse = new Dao_Teacher_Mysql_Course();
    }

    public function getCourseList ($courseType, $teacherid, $isrecommend = 0, $pn = 0, $rn = 20) {
        if (!empty($courseType)) {
            $courseList = $this->getCourseListByType ($courseType, $pn, $rn) ;
        } else if (!empty($teacherid)) {
            $courseList = $this->getCourseListByTeacher ($teacherid, $pn, $rn) ;
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

        $arrAppends = array(
            'order by id desc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoCourse->getListByConds($arrConds, $arrFields, null , $arrAppends);
        if (empty($lists)) {
            return [];
        }
        return array_values($lists);
    }

    public function getCourseTotalByType ($courseType) {
        
        if (empty($courseType)) {
            return 0;
        }

        $arrConds = [
            'status' => 1,
            'coursetype' => $courseType,
        ];

        return (int)$this->daoCourse->getCntByConds($arrConds);
    }

    public function getCourseListByTeacher ($teacherid, $pn = 0, $rn = 20) {
        if (empty($teacherid)) {
            return [];
        }

        $arrConds = [
            'status' => 1,
            'teacherid' => $teacherid,
        ];

        $arrAppends = array(
            'order by id desc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoTeacherCourse->getListByConds($arrConds, $this->daoTeacherCourse->simpleFields, null ,$arrAppends);
        if (empty($lists)) {
            return [];
        }

        $courseIds = array_column($lists, 'courseid');
        $arrAppends = [
            'status = 1',
            "courseid in (" . implode(",", $courseIds) . ")",
        ];
        $lists = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields, NULL, $arrAppends);
        if (empty($lists)) {
            return [];
        }

        return array_values($lists);
    }

    public function getCourseTotalByTeacher ($teacherid) {
        
        if (empty($teacherid)) {
            return 0;
        }

        $arrConds = [
            'status' => 1,
            'teacherid' => $teacherid,
        ];

        return (int)$this->daoTeacherCourse->getCntByConds($arrConds);
    }

    public function getCourseListByRecommend ($isrecommend = 0, $pn = 0, $rn = 20) {
        $arrConds = [
            'status' => 1,
        ];

        $arrAppends = array(
            "limit {$pn} , {$rn}",
        );

        if ($isrecommend == 1) {
            $arrAppends[] =  'order by id desc';
        } else {
            $arrAppends[] =  'order by recommend desc, id desc';
        }

        $lists = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields, null , $arrAppends);
        if (empty($lists)) {
            return [];
        }
        
        return array_values($lists);
    }

    public function getCourseTotalByRecommend () {
        $arrConds = [
            'status' => 1,
        ];

        return (int)$this->daoTeacherCourse->getCntByConds($arrConds);
    }

    public function getCourseTotal ($courseType, $teacherid) {
        if (!empty($courseType)) {
            $total = $this->getCourseTotalByType ($courseType) ;
        } else if (!empty($teacherid)) {
            $total = $this->getCourseTotalByTeacher ($teacherid) ;
        } else {
            $total = $this->getCourseTotalByRecommend ();
        }
        return $total;
    }

    public function getCourseDetails ($courseid) {
        $arrConds = [
            'status' => 1,
            'courseid' => $courseid,
        ];

        $details = $this->daoCourse->getRecordByConds($arrConds, $this->daoCourse->arrFieldsMap);
        if (empty($details)) {
            return [];
        }

        $details['createtime'] = date('Y年m月d日', $details['createtime']);
        return $details;
    }

    public function getCourseTypes ($courseType) {
        $lists = [];
        foreach (self::COURSE_TYPE_LISTS as $index => $item) {
            $item['active'] = ($item['id'] == $courseType) ? 1 : 0;
            $lists[] = $item;
        }
        return $lists;
    }
}