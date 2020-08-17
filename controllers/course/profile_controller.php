<?php
class Controller_Profile extends Zy_Core_Controller{

    public function getCourseDetails () {
        $courseid = empty($this->_request['courseid']) ? 0 : intval($this->_request['courseid']);
        if (empty($courseid)) {
            $this->error(405, '无法检索到相关课程');
        }

        $serivce = new  Service_Course_Lists ();
        $details = $serivce->getCourseDetails($courseid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关课程');
        }

        $output = [
            'details'       => $details,
            'coursetype'    => $serivce->getCourseTypes($details['coursetype']),
            'paytype'       => Service_Pay_Order::PAY_TYPE,
        ];

        return $output;
    }
}
