<?php

class Service_Course_Lists {

    private $daoCourse ;

    private $daoTeacherCourse ;

    private $daoTeacher;

    const COURSE_TYPE_LISTS = [
        ['id'    => 'toefl',    'name'  => 'TOEFL',],
        ['id'    => 'ielts',    'name'  => 'IELTS',],
        ['id'    => 'sat',      'name'  => 'SAT',],
        ['id'    => 'sat2',     'name'  => 'SATⅡ/AP',],
        ['id'    => 'gre',      'name'  => 'GRE/GMAT',],
        ['id'    => 'other',    'name'  => '其他课程',],
    ];

    const COURSE_STATUS_ONLINE  = 1;
    const COURSE_STATUS_OFFLINE = 2;

    public function __construct() {
        $this->daoCourse = new Dao_Course_Mysql_Course () ;
        $this->daoTeacher = new Dao_Teacher_Mysql_Teacher();
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
            $course['createtime'] = date('Y年m月d日', $course['createtime']);
            $course['updatetime'] = date('Y年m月d日', $course['updatetime']);
            $courseList[$index] = $course;
        }

        return array_values($courseList);
    }

    public function getCourseListByType ($courseType, $pn = 0, $rn = 20) {

        $arrConds = [
            'status' => self::COURSE_STATUS_ONLINE,
            'coursetype' => $courseType,
        ];

        $arrFields = $this->daoCourse->simpleFields;

        $arrAppends = array(
            'order by courseid desc',
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
            'status' => self::COURSE_STATUS_ONLINE,
            'coursetype' => $courseType,
        ];

        return (int)$this->daoCourse->getCntByConds($arrConds);
    }

    public function getCourseListByTeacher ($teacherid, $pn = 0, $rn = 20) {
        $arrConds = [
            'status' => Service_Teacher_Lists::TEACHER_STATUS_ONLINE,
            'teacherid' => $teacherid,
        ];

        $arrAppends = array(
            'order by courseid desc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoTeacherCourse->getListByConds($arrConds, $this->daoTeacherCourse->simpleFields, null ,$arrAppends);
        if (empty($lists)) {
            return [];
        }

        $courseIds = array_column($lists, 'courseid');
        $arrAppends = [
            'status = ' . self::COURSE_STATUS_ONLINE,
            "courseid in (" . implode(",", $courseIds) . ")",
        ];
        $lists = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields, NULL, $arrAppends);
        if (empty($lists)) {
            return [];
        }

        return array_values($lists);
    }

    public function getCourseTotalByTeacher ($teacherid) {
        $arrConds = [
            'status' => Service_Teacher_Lists::TEACHER_STATUS_ONLINE,
            'teacherid' => $teacherid,
        ];

        return (int)$this->daoTeacherCourse->getCntByConds($arrConds);
    }

    public function getCourseListByRecommend ($isrecommend = 0, $pn = 0, $rn = 20) {
        $arrConds = [
            'status' => self::COURSE_STATUS_ONLINE
        ];

        if ($isrecommend == 1) {
            $arrConds['recommend'] = 1;
        }

        $arrAppends[] =  'order by recommend desc, courseid desc';

        $arrAppends[] = "limit {$pn} , {$rn}";

        $lists = $this->daoCourse->getListByConds($arrConds, $this->daoCourse->simpleFields, null , $arrAppends);
        if (empty($lists)) {
            return [];
        }
        
        return array_values($lists);
    }

    public function getCourseTotalByRecommend () {
        $arrConds = [
            'status' => self::COURSE_STATUS_ONLINE,
        ];

        return (int)$this->daoCourse->getCntByConds($arrConds);
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
            'status' => self::COURSE_STATUS_ONLINE,
            'courseid' => $courseid,
        ];

        $details = $this->daoCourse->getRecordByConds($arrConds, $this->daoCourse->arrFieldsMap);
        if (empty($details)) {
            return [];
        }

        $teacherids = $this->daoTeacherCourse->getListByConds($arrConds, $this->daoTeacherCourse->simpleFields, NULL, NULL);
        $teacherids = array_column($teacherids, 'teacherid');
        $arrConds = [
            'status = ' . self::COURSE_STATUS_ONLINE,
            "teacherid in (" . implode(",", $teacherids) . ")",
        ];

        $teachers = $this->daoTeacher->getListByConds($arrConds, $this->daoTeacher->simpleFields);
        $teachers = empty($teachers) ? [] : $teachers;
        if (!empty($teachers)) {
            foreach ($teachers as $index=>$teacher) {
                $teachers[$index] = [
                    "teacherid" => $teacher['teacherid'],
                    "teachername" => $teacher["teachername"],
                    "teacheravatar" => $teacher["teacheravatar"],
                ];
            }
        }

        $details['teachers']   = $teachers;
        $details['createtime'] = date('Y年m月d日', $details['createtime']);
        $details['updatetime'] = date('Y年m月d日', $details['updatetime']);
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