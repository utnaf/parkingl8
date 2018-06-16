<?php

namespace Parking\Http\Controllers\Auth;

use Parking\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;

/** @codeCoverageIgnore */
class ResetPasswordController extends Controller {
    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/dashboard';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('guest');
    }
}
