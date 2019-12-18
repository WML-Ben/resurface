<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;

class ErrorsController extends CommonController
{
    public function error404()
    {
        if (env('ERRORS_RETURN_HOME', false)) {
            $errorTitle = $this->lang == 'sp' ? 'Página no encontrada' : 'Page Not Found';
            $errorText = $this->lang == 'sp' ? 'La página solicitada no ha sido encontrada. Compruebe que la URL que ha especificado est&eacute; correcta e inténtelo de nuevo.' : 'The page requested could not be found. Check the URL you entered for any mistakes and try again.';

            $message = [
                'type'    => 'error',
                'title'   => $errorTitle,
                'message' => $errorText,
            ];

            return redirect()->to('/')->withCustom(json_encode($message));
        } else {
            $data = [
                'config' => \App\Config::fetch(),
                'seo'          => [
                    'pageTitlePrefix' => 'Error 404 | ',
                    'pageTitle'       => 'Page not found'
                ],
                'unknownUrl' => session()->get('unknownUrl'),
            ];

            return response()->view('errors.404', $data, 404);  // 3rd parameter: error code. Sending 404 to avoid soft-404 error

            // return view('errors.404', $data);
        }
    }

}
