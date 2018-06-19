<?php

namespace Parking\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\App;
use Parking\Repositories\IssueRepository;
use Parking\Service\Configuration\LocaleConfigurationProvider;
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
    public function index(IssueRepository $issueRepository) {
        return view(
            'dashboard',
            [
                'loadApp' => true
            ]
        );
    }

    public function locale(string $locale): RedirectResponse {
        $sanitizedLocale = strtolower($locale);
        if(\in_array($sanitizedLocale, LocaleConfigurationProvider::AVAILABLE_LOCALES, true)) {
            session(['locale' => $sanitizedLocale]);
        }

        return redirect()->back();
    }
}
