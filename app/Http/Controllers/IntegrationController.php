<?php

namespace App\Http\Controllers;

use App\Services\Google\GoogleAuthService;
use Illuminate\Http\Request;

class IntegrationController extends Controller
{
    /**
     * @var GoogleAuthService
     */
    protected $googleAuthService;

    /**
     * IntegrationController constructor.
     * @param GoogleAuthService $googleAuthService
     */
    public function __construct(GoogleAuthService $googleAuthService)
    {
        $this->googleAuthService = $googleAuthService;
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function integrateGoogle()
    {
        $authUrl = $this->googleAuthService->getAuthUrl();
        return redirect($authUrl);
    }


    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function googleAuth(Request $request)
    {
        try {
            $this->googleAuthService->googleCallback($request);
        }catch (\Exception $exception){
            return redirect()->back()->withErrors(['msg' => 'Oops! Something went wrong.']);
        }
        return view('welcome');
    }
}
