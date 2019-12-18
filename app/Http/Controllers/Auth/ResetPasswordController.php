<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\CommonController;
use Illuminate\Foundation\Auth\ResetsPasswords;

class ResetPasswordController extends CommonController
{

    use ResetsPasswords;

    protected $redirectTo = '/';

    public function __construct()
    {
        $this->middleware('guest');

        parent::__construct();
    }
}
