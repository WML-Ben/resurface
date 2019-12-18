<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Model  Observers:

        \App\Order::observe(\App\Observers\OrderObserver::class);


        /** jButton params:
        'buttonLabel'
        'params:
            'button-id'     default: '',
            'button-class'  default: btn-primary,
        'attributes'
            any html button allowed attribute
         */
        \Form::component('jButton', 'components.form.button', ['buttonLabel', 'params' => []]);
        
        /** jCancelAndButton params:
        'buttonLabel'
        'attributes:
            'button-id',
            'cancel-id',
            'cancel-label',
         */
        \Form::component('jCancelAndButton', 'components.form.cancel-button', ['buttonLabel', 'params' => []]);
        
        /** jSearchForm params:
         *
         * 'searchRoute',   as route('route-name', [params])
         * 'cancelRoute',   as route('route-name', [params])
         * 'params:
         *     'button-label'     default: 'Search',
         */
        \Form::component('jSearchForm', 'components.form.search-form', ['needle', 'searchRoute', 'cancelRoute', 'params' => []]);

        /** jDeleteForm params:
         *
         * 'route',   as route('route-name', [params])
         */
        \Form::component('jDeleteForm', 'components.form.delete-form', ['route']);

        /** jPaginator params:
         *
         * 'collection',
         * 'routeName',
         *
         * 'params:
         *      'query',            default: http_build_query(Input::except(['page', '_token']))
         *      'routeParams',      default: []
         *      'pageLimits'        default: [10, 20],
         *      'links',            default: 7,
         *      'ul-class'          default: pagination custom-pagination,
         *      'li-class',         default: same ('li-class')
         *      'li-first-class',
         *      'li-last-class',
         *      'li-edge-class',
         *      'li-inner-class',
         *      'selected-class'    default: 'active selected'
         *      'disabled-class'    default: 'disabled'
         *      'first-caption',
         *      'first-caption'     default: '«',
         *      'last-caption'      default: '»',
         *
         */
        \Form::component('jPaginator', 'components.pagination.paginator', ['collection', 'routeName', 'params' => []]);

        /** jSpinner params:
         * 'label',
         * 'id',
         * 'value',
         * 'min',
         * 'max',
         * 'start',
         * 'step',
         * 'hint',
         * 'required',
         * 'attributes' => [], // other field attributes
         * 'iconClass',
         */
        \Form::component('jSpinner', 'components.form.spinner', ['name', 'params' => []]);
        
        /** jText, jPassword & jTextarea params:
         * 'label',
         * 'id',
         * 'value',
         * 'hint',
         * 'required',
         * 'placeholder',
         * 'attributes' => [], // other field attributes
         * 'iconClass',
         */
        \Form::component('jText', 'components.form.text', ['name', 'params' => []]);
        \Form::component('jText2', 'components.form.text2', ['name', 'params' => []]); // not using Form::text
        \Form::component('jPassword', 'components.form.password', ['name', 'params' => []]);
        \Form::component('jTextarea', 'components.form.textarea', ['name', 'params' => []]);
        
        /** jCalendar params:
        'label',
        'id',
        'value',
        'format' default: m/d/Y for jCalendar,  'm/d/Y h:i A' for jDateTimePicker
        'hint',
        'required',
        'placeholder',
        'iconClass',
        'attributes' => [], // other field attributes
         */
        \Form::component('jCalendar', 'components.form.calendar', ['name', 'params' => []]);

        /** jDatePicker, jTimerPicker & jDateTimePicker params:
        'label',
        'id',
        'value',
        'format' default: m/d/Y for jCalendar,  h:i A for jTime  & 'm/d/Y h:i A' for jDateTimePicker
        'hint',
        'required',
        'placeholder',
        'iconClass',
        'language',    spanish: es
         'attributes' => [], // other field attributes
         */
        \Form::component('jDatePicker', 'components.form.datepicker', ['name', 'params' => []]);
        \Form::component('jDateTimePicker', 'components.form.datetimepicker', ['name', 'params' => []]);
        \Form::component('jTimePicker', 'components.form.timepicker', ['name', 'params' => []]);
        
        /** jDateRangePicker params:
        'from',    name (id) of the related hidden input fields
        'to',      name (id) of the related hidden input fields

         id',      id of the visible input text field
        'label',
        'value',
        'format' default: m/d/Y for jCalendar,  'm/d/Y h:i A' for jDateTimePicker
        'hint',
        'required',
        'placeholder',
        'iconClass',
        'language',    spanish: es
        'attributes' => [], // other field attributes
         */
        \Form::component('jDateRangePicker', 'components.form.daterangepicker', ['from', 'to', 'params' => []]);

        /** jSwitch params:
         * 'label',
         * 'id',
         * 'on',
         * 'off'
         * 'checked',
         */
        \Form::component('jSwitch', 'components.form.switch', ['name', 'params' => []]);

        /** jSelect params:
         * 'label',
         * 'id',
         * 'required',
         * 'attributes' => [], // other field attributes
         * 'iconClass',
         */
        \Form::component('jSelect', 'components.form.select', ['name', 'data', 'params' => []]);

        \Form::component('jSelect2', 'components.form.select2', ['name', 'data', 'params' => []]);

        /** jMultiSelect params:
        'label',
        'hint',
        'id',
        'selected'       // num array of ids
        'required',
        'icon',             // true or false
        'iconClass',
        'attributes' => [], // other field attributes
         */
        \Form::component('jMultiSelect', 'components.form.multiselect', ['name', 'data', 'params' => []]);
        \Form::component('jRadioSelect', 'components.form.radioselect', ['name', 'data', 'params' => []]);

        /** jSelectMulti params:
         * 'label',
         * 'id',
         * 'required',
         * 'keys' :  num array of ids
         * 'all'  : true, false: include option Select All in list
         * 'attributes' => [], // other field attributes
         */
        \Form::component('jSelectMulti', 'components.form.select_multi', ['name', 'data', 'params' => []]);

        /** jDropzone params:   ( container id will be "dropzone_{{ $id }}" )
         * 'name',
         * 'class',
         * 'label',
         * label-class,
         * 'hint',
         * 'image',
         * 'id',         // object (row) id
         * 'width',
         * 'height',
         * 'required',
         * 'originalSize',
         * 'oldImageName',
         * 'resize',
         * 'container_id',
         */
        \Form::component('jDropzone', 'components.form.dropzone', ['id', 'uploadUrl', 'removeUrl', 'params' => []]);

        /** jVerification params:
         * 'id',
         * 'name',
         * 'placeholder',
         * 'iconClass',
         */
        \Form::component('jVerification', 'components.form.verification', ['equation', 'params' => []]);

        /** jVerification params:
         * 'submit-id',
         * 'submit-label',
         * 'cancel-id',
         * 'cancel-label',
         */
        \Form::component('jCancelSubmit', 'components.form.cancel-submit', ['params' => []]);

        /** jVerification params:
         * 'submit-id',
         * 'submit-label',
         */
        \Form::component('jSubmit', 'components.form.submit', ['params' => []]);

        /** jCheckbox params:
         * 'label',
         * 'id',
         * 'title',  // add tooltip if defined
         */
        \Form::component('jCheckbox', 'components.form.checkbox', ['name', 'params' => []]);

        /** jMce params:
         * 'label',
         * 'value',
         * 'required', default: false
         * 'id',
         * 'hint',
         * 'title',
         */
        \Form::component('jMce', 'components.form.mce', ['name', 'params' => []]);

        /** jShow params:
         * 'label',
         * 'class',
         *  iconClass
         */
        \Form::component('jShow', 'components.form.show', ['value', 'params' => []]);


        /**
         *  Custom validation rules:
         */
        
        Validator::extend('route_name_or_full_url', 'App\Validators\CustomValidators@validateRouteNameOrFullUrl');
        Validator::extend('full_url', 'App\Validators\CustomValidators@validateFullUrl');
        Validator::extend('route_name', 'App\Validators\CustomValidators@validateRouteName');
        Validator::extend('password', 'App\Validators\CustomValidators@validatePassword');
        Validator::extend('code', 'App\Validators\CustomValidators@validateCode');

        Validator::extend('positive_between', 'App\Validators\CustomValidators@validatePositiveBetween');

        Validator::replacer('positive_between', function ($message, $attribute, $rule, $parameters) {
            $patterns = ['/\:min/', '/\:max/'];
            $replacements = [$parameters[0], $parameters[1]];

            return preg_replace($patterns, $replacements, $message);
        });

        Validator::extend('text', 'App\Validators\CustomValidators@validateText');
        Validator::extend('plain_text', 'App\Validators\CustomValidators@validatePlainText');
        Validator::extend('slug', 'App\Validators\CustomValidators@validateSlug');
        Validator::extend('identifier', 'App\Validators\CustomValidators@validateIdentifier');
        Validator::extend('file_name', 'App\Validators\CustomValidators@validateFileName');
        Validator::extend('positive', 'App\Validators\CustomValidators@validatePositive');
        Validator::extend('zero_or_positive', 'App\Validators\CustomValidators@validateZeroOrPositive');
        Validator::extend('float', 'App\Validators\CustomValidators@validateFloat');
        Validator::extend('currency', 'App\Validators\CustomValidators@validateCurrency');
        Validator::extend('zip_code', 'App\Validators\CustomValidators@validateZipcCode');
        Validator::extend('postal_code', 'App\Validators\CustomValidators@validatePostalCode');
        Validator::extend('person_name', 'App\Validators\CustomValidators@validatePersonName');
        Validator::extend('phone', 'App\Validators\CustomValidators@validatePhone');
        Validator::extend('credit_card', 'App\Validators\CustomValidators@validateCreditCard');
        Validator::extend('instagram', 'App\Validators\CustomValidators@validateInstagram');
        Validator::extend('address', 'App\Validators\CustomValidators@validateAddress');
        Validator::extend('location', 'App\Validators\CustomValidators@validateLocation');
        Validator::extend('iso_date', 'App\Validators\CustomValidators@validateIsoDate');
        Validator::extend('us_date', 'App\Validators\CustomValidators@validateUsDate');
        Validator::extend('sp_date', 'App\Validators\CustomValidators@validateSpDate');
        Validator::extend('iso_date_time', 'App\Validators\CustomValidators@validateIsoDateTime');
        Validator::extend('us_date_time', 'App\Validators\CustomValidators@validateUsDateTime');
        Validator::extend('any_date', 'App\Validators\CustomValidators@validateAnyDate');
        Validator::extend('time', 'App\Validators\CustomValidators@validateTime');
        Validator::extend('iso_date_time', 'App\Validators\CustomValidators@validateIsoDateTime');
        Validator::extend('us_date_time', 'App\Validators\CustomValidators@validateUsDateTime');
        Validator::extend('sp_date_time', 'App\Validators\CustomValidators@validateSpDateTime');
        Validator::extend('relative_url', 'App\Validators\CustomValidators@validateRelativeUrl');
        Validator::extend('url_segment', 'App\Validators\CustomValidators@validateUrlSegment');
        Validator::extend('friendly_url_segments', 'App\Validators\CustomValidators@validateFriendlyUrlSegments');
        Validator::extend('friendly_url_segments', 'App\Validators\CustomValidators@validateFriendlyUrlSegments');
        Validator::extend('subdomain', 'App\Validators\CustomValidators@validateSubdomain');
        Validator::extend('custom_url_segment', 'App\Validators\CustomValidators@validateCustomUrlSegment');
        Validator::extend('int_percent', 'App\Validators\CustomValidators@validateIntPercent');
        Validator::extend('boolean', 'App\Validators\CustomValidators@validateBoolean');
        Validator::extend('token', 'App\Validators\CustomValidators@validateAlphaNumeric');
        Validator::extend('alpha_numeric', 'App\Validators\CustomValidators@validateAlphaNumeric');
        Validator::extend('lower', 'App\Validators\CustomValidators@validateLower');
        Validator::extend('video_id', 'App\Validators\CustomValidators@validateVideoId');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
