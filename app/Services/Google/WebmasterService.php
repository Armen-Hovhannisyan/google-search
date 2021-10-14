<?php

namespace App\Services\Google;

use Carbon\Carbon;
use Google\Client;
use Google_Service_Webmasters;
use Google_Service_Webmasters_SearchAnalyticsQueryRequest;

class WebmasterService
{

    /**
     * @param Client $client
     * @param string $url
     * @param array $params
     * @return \Google\Service\Webmasters\SearchAnalyticsQueryResponse
     * @throws \Exception
     */
    public function query(Client $client, string $url, $params = [])
    {
        $startDate =$params['startDate'] ?? Carbon::now()->subMonth();
        $endDate = $params['endDate'] ?? Carbon::now();
        $rowLimit = $params['rowLimit'] ?? 10;

        $request = new Google_Service_Webmasters_SearchAnalyticsQueryRequest();

        $request->setStartDate($startDate->format('Y-m-d'));
        $request->setEndDate($endDate->format('Y-m-d'));
        $request->setRowLimit($rowLimit);
        $request->setDimensions(['query','page']);
        $request->setSearchType('web');
        $request->setAggregationType('byPage');
        try {
            $service = new Google_Service_Webmasters($client);
            $query = $service->searchanalytics->query($url, $request);
        }catch(\Exception $exception){
            throw $exception;
        }

        return $query;
    }


    /**
     * @param Client $client
     * @return \Google\Service\Webmasters\SitesListResponse
     * @throws \Exception
     */
    public function listSites(Client $client)
    {
        try {
            $service = new Google_Service_Webmasters($client);
            $listSites = $service->sites->listSites();
        }catch(\Exception $exception){
            throw $exception;
        }

        return $listSites;
    }
}
