<?php

namespace App\Http\Controllers;

use App\Services\Google\AnalyticService;
use App\Services\Google\GoogleAuthService;
use App\Services\Google\WebmasterService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    /**
     * @var WebmasterService
     */
    protected $webmasterService;
    /**
     * @var AnalyticService
     */
    protected $analyticService;
    /**
     * @var GoogleAuthService
     */
    protected $googleAuthService;


    /**
     * AnalyticsController constructor.
     * @param GoogleAuthService $googleAuthService
     * @param WebmasterService $webmasterService
     * @param AnalyticService $analyticService
     */
    public function __construct(GoogleAuthService $googleAuthService, WebmasterService $webmasterService, AnalyticService $analyticService)
    {
        $this->webmasterService = $webmasterService;
        $this->analyticService = $analyticService;
        $this->googleAuthService = $googleAuthService;
    }


    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function getListSites()
    {
        $client = $this->googleAuthService->getGoogleClientWithToken();
        if (!$client) {
            $authUrl = $this->googleAuthService->getAuthUrl();
            return redirect($authUrl);
        }
        try {
            $listSites = $this->webmasterService->listSites($client);
            $urlList = $this->analyticService->getSiteUrls($listSites);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg' => 'User does not have permission']);
        }

        return view('welcome', compact('urlList'));
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function searchAnalytics(Request $request)
    {
        $client = $this->googleAuthService->getGoogleClientWithToken();
        if (!$client) {
            $authUrl = $this->googleAuthService->getAuthUrl();
            return redirect($authUrl);
        }
        $url = $request->get('url');
        try {
            $queryResults = $this->webmasterService->query($client, $url);
            $analyticResults = $this->analyticService->getAnalyticResult($queryResults);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg' => 'User does not have permission for this site']);
        }

        return view('welcome', compact('url','analyticResults'));
    }

}
