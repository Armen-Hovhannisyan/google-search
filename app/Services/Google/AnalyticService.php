<?php

namespace App\Services\Google;

class AnalyticService
{
    /**
     * @param $queryResults
     * @return array|mixed
     * @throws \Exception
     */
    public function getAnalyticResult($queryResults)
    {
        try {
            $resultArr = [];
            foreach($queryResults as $result) {
                $resultArr[] = [
                    'keyword' => $this->getKeyword($result),
                    'link' => $this->getLink($result),
                    'clicks' => $result->clicks,
                    'impressions' => $result->impressions,
                    'ctr' => $result->ctr,
                    'avg_position' => $result->position,
                ];
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $resultArr;
    }

    public function getSiteUrls($siteList)
    {
        try {
            $result = [];
            foreach($siteList->siteEntry as $site) {
                if($site->siteUrl) {
                    $result[] =  $site->siteUrl;
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
        return $result;
    }

    /**
     * @param $result
     * @return mixed
     */
    protected function getKeyword($result)
    {
        return $result->keys[0];
    }


    /**
     * @param $result
     * @return mixed
     */
    protected function getLink($result)
    {
        return $result->keys[1];
    }

}
