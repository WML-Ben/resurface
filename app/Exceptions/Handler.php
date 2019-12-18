<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\MessageBag;
use \Illuminate\Database\Eloquent\ModelNotFoundException;
use \Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof TokenMismatchException) {
            $errors = new MessageBag(['csrf_error' => 'Your session has expired.']);

            return redirect()->back()->withErrors($errors)->withInput(\Input::except('password'));
        }

        if ($exception instanceof TooManyRequestsHttpException) {
            return redirect()->back()->withError('Too many attempts. Try again later.');
        }

        $config = \App\Config::fetch();

        $siteUrl = rtrim(env('APP_URL'), '/');
        $s3 = env('S3_ACTIVE', false);
        $s3Url = env('S3_URL', false);
        $s3Public = env('S3_PUBLIC', false);
        $protocol = $request->secure() ? 'https://' : 'http://';
        $publicUrl = ($s3 && $s3Public) ? $protocol . $s3Url . '/public' : $siteUrl;

        if ($this->isHttpException($exception)) {
            $statusCode = $exception->getStatusCode();

            if (env('EMAIL_ERRORS', false)) {
                $content = '<p><b>Code Error: </b> ' . $statusCode . '</p>';
                $content .= '<p><b>Date: </b>' . now(session()->get('timezone'))->format('m/d/Y g:i: A') . '</p>';
                if (auth()->check()) {
                    $content .= '<p><b>User: </b>' . auth()->user()->fullName . '</p>';
                }
                $content .= '<p><b>URL: </b>' . \Request::url() . '</p>';
                $content .= '<p><b>File: </b>' . $exception->getFile() . '</p>';
                $content .= '<p><b>Line: </b>' . $exception->getLine() . '</p>';
                if ($exception->getMessage()) {
                    $content .= '<p><b>Message:</b><br>' . $exception->getMessage() . '</p>';
                }
                if (env('EMAIL_TRACE_INFO', false)) {
                    $content .= '<p><b>Trace:</b><br>' . $exception->getTraceAsString() . '</p>';
                }

                $to = env('EMAIL_LOCAL', false) ? 'user@localhost.com' : $config['adminEmail'];

                $subject = 'Exception ' . $statusCode . ' on ' . env('APP_FULL_NAME', 'Production server!!');

                $tags = [
                    'mail_from_address' => $config['noReplyEmail'] ?? env('MAIL_FROM_ADDRESS'),
                    'mail_from_name'    => $config['company'] ?? env('MAIL_FROM_NAME'),

                    'config'    => $config,
                    'siteUrl'   => $siteUrl,
                    'publicUrl' => $publicUrl,

                    'subject' => $subject,
                    'content' => $content,
                ];

                \Mail::to($to)->send(new \App\Mailer\JvcMailer('emails.notification', $tags));
            }

            switch ($statusCode) {
                case 403:
                    $pageTitle = 'Forbidden';
                    $errorTitle = 'Access Forbidden';
                    $errorText = 'You are not allowed to perform the requested action or don\'t have access to requested section or resource.';
                    break;
                case 404:
                    // when done \App::abort(404) anywhere in the application. For unknown url, fallback roue is trigered
                    $pageTitle = 'Page not found';
                    $errorTitle = 'Page Not Found';
                    $errorText = 'The page requested could not be found. Check the URL you entered for any mistakes and try again.';
                    $blade = '404';
                    break;
                case 500:
                default:
                    $pageTitle = 'Critical error';
                    $errorTitle = 'A Critical Server Error Has Occurred';
                    $errorText = 'Unfortunately a critical server error has occurred. We apologize for this and we assure you our technical team will fix it shortly. Thanks!';
                    break;
                case 503:
                    $pageTitle = 'Maintenance mode';
                    $errorTitle = 'This site is in maintenance mode';
                    $errorText = 'Be right back.';
                    $blade = '503';
                    break;
            }

            if (env('ERRORS_RETURN_HOME', false)) {
                $message = [
                    'type'    => 'error',
                    'title'   => $errorTitle,
                    'message' => $errorText,
                ];

                return redirect()->to('/')->withCustom(json_encode($message));
            } else {
                $data = [
                    'siteUrl'    => $siteUrl,
                    'publicUrl'  => $publicUrl,
                    'config'     => $config,
                    'errorCode'  => $statusCode,
                    'errorTitle' => $errorTitle,
                    'errorText'  => $errorText,

                    'seo' => [
                        'pageTitlePrefix' => $statusCode . ' | ',
                        'pageTitle'       => $pageTitle,
                    ],
                ];

                return response()->view('errors.'. ($blade ?? '404'), $data, $statusCode);
            }
        }

        return parent::render($request, $exception);
    }
}
