<?php

class Service_Banner_Lists {

    private $daoBanner ;

    public function __construct() {
        $this->daoBanner = new Dao_Banner_Mysql_Banner () ;
    }

    public function getBannerList ($pn = 0, $rn = 10){
        $arrConds = array(
            'status' => 1,
        );

        $arrFields = array(
            'bannerid',
            'bannerurl',
            'bannerimg',
        );

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
}