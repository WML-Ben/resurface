<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\CommonController;

use Illuminate\Http\Request;
use App\Http\Requests;

class IdleController extends CommonController
{

    protected function lockOut()
    {
        session()->put('lockout', true);

        if (!str_contains(\URL::previous(), 'lockout')) {
            session()->put('previous_url', \URL::previous());
        }

        $data = [
            'seo' => [
                'pageTitlePrefix' => '',
                'pageTitle'       => 'Unlock Screen',
            ],
        ];

        return view('auth.unlock', $data);
    }

    protected function unlock(Request $request)
    {
        if (\Hash::check($request->password, \Auth::user()->password)){
            session()->forget('previous_url');
            session()->forget('lockout');

            return ($previous_url = session()->get('previous_url')) ? redirect($previous_url) : redirect('/');
        } else {
            return redirect()->back()->withError($this->lang == 'sp' ? 'Credenciales no v&aacute;lidas' : 'Invalid credentials.');
        }
    }

}
