<?php

class Service_Banner_Lists {

    private $daoBanner ;

    public function __construct() {
        $this->daoBanner = new Dao_Banner_Mysql_Banner () ;
    }

    public function getBannerList ($pn = 0, $rn = 5){
        $arrConds = array(
            'status' => 1,
        );

        $arrFields = $this->daoBanner->simpleFields;

        $arrAppends = array(
            'order by weight desc, id desc',
            "limit {$pn} , {$rn}",
        );

        $lists = $this->daoBanner->getListByConds($arrConds, $arrFields, NULL, $arrAppends);
        if (empty($lists)) {
            return [];
        }

        return array_values($lists);
    }
}