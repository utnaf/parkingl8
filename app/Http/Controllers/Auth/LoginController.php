<?php

namespace Parking\Http\Controllers\Auth;

use Parking\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/** @codeCoverageIgnore */
class LoginController extends Controller {
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
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
        $this->middleware('guest')->except('logout');
    }
}
