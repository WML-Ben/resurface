<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\CommonController;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends CommonController
{

    use SendsPasswordResetEmails;

    public function __construct()
    {
        $this->middleware('guest');

        parent::__construct();
    }
}