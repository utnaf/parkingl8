<?php

namespace Parking\Http\Controllers\Auth;

use Parking\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/** @codeCoverageIgnore */
class ForgotPasswordController extends Controller {
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }
}
