<?php

class Service_Campus_Lists {

    private $daoCampus ;

    public function __construct() {
        $this->daoCampus = new Dao_Campus_Mysql_Campus () ;
    }

    const CAMPUS_STATUS_ONLINE  = 1;
    const CAMPUS_STATUS_OFFLINE = 2;

    public function getCampusList ($campusid) {

        $arrConds = [
            'status' => self::CAMPUS_STATUS_ONLINE,
            'articleid > 0',
        ];

        $arrFields = $this->daoCampus->simpleFields;

        $arrAppends = array(
            'order by campusid asc',
        );

        $lists = $this->daoCampus->getListByConds($arrConds, $arrFields, null , $arrAppends);
        if (empty($lists)) {
            return [0, []];
        }

        $articleid = null;

        foreach ($lists as $index => $item) {
            $lists[$index] = [
                'campusid'    => $item['campusid'],
                'campusname'  => $item['campusname'],
                'active'      => empty($campusid) ? 1 : ($item['campusid'] == $campusid ? 1 : 0),
            ];
            $campusid = $item['campusid'];

            if ($lists[$index]['active'] == 1) {
                $articleid = $item['articleid'];
            }
        }

        $lists = array_values($lists);

        if (empty($articleid)) {
            $lists[0]['active'] = 1;
            $articleid = $lists[0]['articleid'];
        }

        return [$articleid, $lists];
    }
}