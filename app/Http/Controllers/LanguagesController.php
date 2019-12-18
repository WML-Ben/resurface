<?php namespace App\Http\Controllers;

class LanguagesController extends FrontEndBaseController
{

    public function language($lang)
    {
        if (!empty($lang) && in_array($lang, ['sp', 'en'])) {
            session()->put('lang', $lang);

            if (empty($this->action['previousRouteName'])) {
                return redirect()->back();
            }

            $routeName = preg_replace('/(_en|_sp)$/', '_'.$lang, $this->action['previousRouteName']);

            return redirect()->route($routeName, $this->action['previousRouteParameters']);
        } else {
            return redirect()->back();
        }
    }

}