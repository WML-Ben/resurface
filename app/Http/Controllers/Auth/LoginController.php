<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\Http\Controllers\CommonController;

use Illuminate\Support\Facades\Auth;

class LoginController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo;

    protected $redirectPath;
    protected $redirectAfterLogout;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $segment = str_replace(rtrim(asset('/'), '/'), '', \URL::previous());
        $lastPage = session()->get('lastPage');

        if (!in_array($segment, ['/login', '/logout'])) {
            $lastPage = $segment;
        }
        session()->put('lastPage', $lastPage);

        $this->redirectPath = ($this->afterLoginGoTo == 'previousPage') ? $lastPage : $this->afterLoginGoTo;
        $this->redirectTo = $this->redirectPath;
        $this->redirectAfterLogout = $this->afterLogoutGoTo;

        $this->middleware('guest', ['except' => 'logout']);
    }
    
    // overwrite original function on Illuminate\Foundation\AuthAuthenticatesUsers:

    protected function credentials()
    {
        return array_merge(\Request::only($this->username(), 'password'), ['is_employee' => 1, 'disabled' => 0]);
    }
}
