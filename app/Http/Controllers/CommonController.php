<?php namespace App\Http\Controllers;

use ActionTrait;
use XuacTrait;

class CommonController extends Controller
{
    use ActionTrait, XuacTrait;

    const ERROR_NOT_ENOUGH_PRIVILEGES = 'You are not allowed to perform the requested action.';

    protected $_crud = [
        'list',
        'search',
        'show',
        'create',
        'update',
        'disable',
        'delete',
    ];

    public
        $s3,
        $protocol,
        $siteUrl,
        $publicUrl,
        $mediaUrl,
        $lang,
        $action,
        $conf,
        $defaultSEO,
        $timezone = null,
        $afterLoginGoTo = '/',       // 'home' or 'admin' or 'previousPage'
        $afterLogoutGoTo = 'login',                 // or 'login'
        $afterResetPasswordGoTo = '/',
        $maxLoginAttempts = 3,
        $lockoutTime = 60;                      // time in seconds

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!env('APP_DEBUG') && auth()->check() && auth()->user()->hasPrivilege('debug_true')) {
                \Config::set('app.debug', true);
            }

            if (!session()->has('timezone')) {
                session()->put('timezone', 'America/New_York');
            }
            $this->timezone = session()->get('timezone');

            $this->siteUrl = rtrim(asset('/'), '/');

            session()->put('siteUrl', $this->siteUrl);
            view()->share('siteUrl', $this->siteUrl);

            $this->s3 = env('S3_ACTIVE', false);
            $s3Url = env('S3_URL', false);
            $s3Public = env('S3_PUBLIC', false);

            $this->protocol = $request->secure() ? 'https://' : 'http://';
            session()->put('protocol', $this->protocol);
            view()->share('protocol', $this->protocol);

            $this->publicUrl = ($this->s3 && $s3Public) ? $this->protocol . $s3Url . '/public' : $this->siteUrl;
            session()->put('publicUrl', $this->publicUrl);
            view()->share('publicUrl', $this->publicUrl);

            $this->mediaUrl = $this->s3 ? $this->protocol . $s3Url . '/media' : $this->publicUrl .'/media';
            session()->put('mediaUrl', $this->mediaUrl);
            view()->share('mediaUrl', $this->mediaUrl);

            $this->action = $this->getAction();
            session()->put('action', $this->action);
            view()->share('action', $this->action);

            $this->conf = \App\Config::reload();   // values are stored as a associative array into session as config and shared to views
    
            $this->lang = 'en';

            session()->put('lang', $this->lang);
            view()->share('lang', $this->lang);

            \App::setLocale($this->lang);
    
            $config = session()->get('config');

            $this->defaultSEO = [
                'pageTitlePrefix' => $config['seoDefaultTitlePrefix'] ?? '',
                'pageTitle'       => $config['seoDefaultTitle'] ?? 'All Paving',
                'description'     => $config['seoDefaultDescription'] ?? 'All Paving Page Description',
                'keywords'        => $config['seoDefaultKeywords'] ?? 'All Paving keywords',
            ];
            view()->share('defaultSEO', $this->defaultSEO);

            /*
            $proposal = \App\Proposal::find(254);

            $proposal->load(['salesManager', 'salesPerson', 'manager']);

            $appointmentData = [
                'started_at'  => now()->addDay(),
                'ended_at'    => now()->addDay()->addHours(1),
            ];

            $services = \App\ServiceCategory::whereIn('id', [1, 2, 6])->orderBy('name')->pluck('name')->toArray();

            $proposal->load(['property', 'salesManager', 'salesPerson', 'manager']);

            event(new \App\Events\NewAppointmentWasScheduled($proposal, $appointmentData, $services));

            dd('ok');
            */

            if (session()->has('error')) {
                view()->share('error', session()->get('error'));
            } else if (session()->has('warning')) {
                view()->share('warning', session()->get('warning'));
            } else if (session()->has('success')) {
                view()->share('success', session()->get('success'));
            } else if (session()->has('info')) {
                view()->share('info', session()->get('info'));
            } else if (session()->has('custom')) {
                view()->share('custom', session()->get('custom'));
            }

            if ($request->isMethod('get') && ! $request->ajax()) {
                if (session()->has('notification_error')) {
                    view()->share('notification_error', session()->get('notification_error'));
                    session()->forget('notification_error');
                } else if (session()->has('notification_warning')) {
                    view()->share('notification_warning', session()->get('notification_warning'));
                    session()->forget('notification_warning');
                } else if (session()->has('notification_success')) {
                    view()->share('notification_success', session()->get('notification_success'));
                    session()->forget('notification_success');
                } else if (session()->has('notification_info')) {
                    view()->share('notification_info', session()->get('notification_info'));
                    session()->forget('notification_info');
                } else if (session()->has('notification_custom')) {
                    view()->share('notification_custom', session()->get('notification_custom'));
                    session()->forget('notification_custom');
                }
    
                if (session()->has('jsEvent')) {
                    view()->share('jsEvent', session()->get('jsEvent'));
                    session()->forget('jsEvent');
                }
                if (session()->has('jsFunction')) {
                    view()->share('jsFunction', session()->get('jsFunction'));
                    session()->forget('jsFunction');
                }
            }

            return $next($request);
        });
    }

    public function convertUsFormatStringDatesToCarbon($inputs, $dates)
    {
        if (!empty($dates) && is_array($dates) && ($dateKeys = array_intersect(array_keys($inputs), $dates))) {
            foreach ($dateKeys as $key) {
                if (!empty($inputs[$key])) {
                    $inputs[$key] = \Carbon\Carbon::createFromFormat('m/d/Y', $inputs[$key]);
                }
            }
        }

        return $inputs;
    }

    public function flashJsEvent($targetElement, $evenName)  // make it available for the next cycle:
    {
        $jsEvent = [
            'target' => $targetElement,
            'name'   => $evenName,
        ];

        session()->put('jsEvent', $jsEvent);
    }

    public function setJsEvent($targetElement, $evenName)       // make it available for present cycle:
    {
        $jsEvent = [
            'target' => $targetElement,
            'name'   => $evenName,
        ];

        view()->share('jsEvent', $jsEvent);
    }

    public function flashJsFunction($name, $arguments = null)  // make it available for the next cycle:
    {
        $jsFunction = [
            'name'      => $name,
            'arguments' => $arguments,
        ];

        session()->put('jsFunction', $jsFunction);
    }

    public function setJsFunction($name, $arguments = null)       // make it available for present cycle:
    {
        $jsFunction = [
            'name'      => $name,
            'arguments' => $arguments,
        ];

        view()->share('jsFunction', $jsFunction);
    }

}
