<?php

namespace Parking\Http\Controllers;

use Parking\Service\ConfigurationService;

/** @codeCoverageIgnore */
class HomeController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ConfigurationService $configurationService) {
        return view(
            'dashboard',
            [
                'config' => $configurationService->get(),
                'loadApp' => true
            ]
        );
    }
}
