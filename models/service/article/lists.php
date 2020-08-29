<?php

class Service_Article_Lists {

    private $articleDao ;

    const ARTICLE_TYPE_NORMAL   = 1;
    const ARTICLE_TYPE_ABROAD   = 2;
    const ARTICLE_TYPE_CAMPUS   = 3;

    const ARTICLE_STATUS_ONLINE  = 1;
    const ARTICLE_STATUS_OFFLINE = 2;

    public function __construct() {
        $this->articleDao = new Dao_Article_Mysql_Article () ;
    }

    public function getArticleList ($articleType, $isrecommend = 0, $pn = 0, $rn = 20) {
        $arrConds = [
            'articletype' => $articleType,
            'status' => self::ARTICLE_STATUS_ONLINE,
        ];
        
        if ($articleType == self::ARTICLE_TYPE_ABROAD) {
            $arrFields = $this->articleDao->arrFieldsMap ;
        } else {
            $arrFields = $this->articleDao->simpleFields ;
        }

        if ($isrecommend == 0) {
            $arrAppends[] = 'order by articleid desc';
        } else {
            $arrAppends[] = 'order by recommend desc, articleid desc';
        }

        $arrAppends[] = "limit {$pn} , {$rn}";

        $articlelist = $this->articleDao->getListByConds($arrConds, $arrFields, NULL, $arrAppends);
        if (empty($articlelist)) {
            return [];
        }

        foreach ($articlelist as $index => $article) {
            $article['createtime']  = date('Y年m月d日', $article['createtime']);
            $article['updatetime']  = date('Y年m月d日', $article['updatetime']);
            $articlelist[$index] = $article;
        }

        return $articlelist;
    }

    public function getRecommendArticleList () {
        return $this->getArticleList(self::ARTICLE_TYPE_NORMAL, 1, 0, 5);
    }

    public function getAbroadArticleList () {
        $articlelist = $this->getArticleList(self::ARTICLE_TYPE_ABROAD, 0);
        $abroadlist  = [];

        if (!empty($articlelist)) {
            foreach ($articlelist as $index => $article) {
                if (empty($article['country'])) {
                    continue;
                }
                $abroadlist['country'] = $article;
            }
        }
        return $abroadlist;
    }

    public function getArticleTotal ($articleType) {
        $arrConds = [
            'articletype' => $articleType,
            'status' => 1,
        ];
        return $this->articleDao->getCntByConds($arrConds);
    }

    public function getArticleDetails ($articleId) {
        $arrConds = array (
            'status' => 1,
            'articleid' => $articleId,
        ) ;
        $article = $this->articleDao->getRecordByConds($arrConds, $this->articleDao->arrFieldsMap);
        
        if (empty($article)) {
            return false;
        }

        $article['createtime'] = date('Y年m月d日', $article['createtime']);
        $article['updatetime'] = date('Y年m月d日', $article['updatetime']);
        return $article;
    }


}