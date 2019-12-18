<?php

/** 2017-07-31
 *
 *  addd sp_date_time
 */

namespace App\Validators;

class CustomValidators
{
    public function validateRouteNameOrFullUrl($attribute, $value)
    {
        return preg_match('/^[0-9a-zA-Z]{1}[0-9a-zA-Z_\.]+$/', $value) || preg_match('/^(ftp|https|http):\/\/(www\.)?[a-zA-Z0-9\-\.]{3,}\.[a-z]{2,3}((\/|\?)[_a-zA-Z0-9#!:\.?+=&%@!\-\/]*)*$/', $value);
    }
    
    public function validateRouteName($attribute, $value)
    {
        return preg_match('/^[0-9a-zA-Z]{1}[0-9a-zA-Z_\.]+$/', $value);  // anything but space
    }

    public function validatePassword($attribute, $value)
    {
        return ! preg_match('/\s+$/', $value);  // anything but space
    }
    
    public function validateCode($attribute, $value)
    {
        return (bool)preg_match('/^[a-zA-Z0-9]{1}[a-zA-Z0-9\-]*[a-zA-Z0-9]{1}$/', $value);
    }

    public function validatePositiveBetween($attribute, $value, $parameters, $validator)
    {
        return (bool)(preg_match('/^[1-9]{1}[0-9]*(\.[0-9]+)?$/', $value) && !empty($parameters) && count($parameters) == 2 && $value >= (integer)$parameters[0] && $value <= (integer)$parameters[1]);
    }

    public function validateText($attribute, $value, $parameters, $validator)
    {
        $value = str_replace('&lt;', '<', $value);
        $value = str_replace('&lt;', '>', $value);

        // <, / or none, any spaces or none, those words, any character or none, and >
        return !preg_match('/(<\/?\s*(javascript|script|onmouseover|onmousedown|onclick)\b.*?>)/i', $value);
    }

    public function validatePlainText($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[^<>]+$/', $value);
    }

    public function validateSlug($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-z]{1}[a-z\-]*$/', $value);
    }

    public function validateIdentifier($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-Z_]{1}[a-zA-Z0-9_]*$/', $value);
    }

    public function validateFileName($attribute, $value, $parameters, $validator)
    {
        return preg_match('/^[0-9a-zA-Z_]{1}[0-9a-zA-Z\s_\-\.]*$/', $value);
    }

    public function validatePositive($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[1-9]{1}[0-9]*(\.[0-9]+)?$/', $value);
    }

    public function validateZeroOrPositive($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9]+$/', $value);
    }

    public function validateFloat($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(\-)?[0-9]+(\.)?([0-9]+)?$/', $value);
    }

    public function validateCurrency($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(0\.)?\d+(\.\d{1,2})?$/', $value);
    }

    public function validateZipcCode($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9]{5}(\-[0-9]{4})?$/', $value);
    }

    public function validatePostalCode($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9a-zA-Z_\s\-]+$/', $value);
    }

    public function validatePersonName($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s\-\'\.]+$/', $value);
    }

    public function validatePhone($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9\(\)\[\]\-\.\s]+$/', $value);
    }

    public function validateCreditCard($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(?:4[0-9]{12}(?:[0-9]{3})?|5[1-5][0-9]{14}|6(?:011|5[0-9][0-9])[0-9]{12}|3[47][0-9]{13}|3(?:0[0-5]|[68][0-9])[0-9]{11}|(?:2131|1800|35\d{3})\d{11})$/', $value);
    }

    public function validateInstagram($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9a-zA-Z@_\-]+$/', $value);
    }

    public function validateAddress($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9a-zA-ZáéíóúñÁÉÍÓÚÑ\s_,\-\.#\%]+$/', $value);
    }

    public function validateLocation($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-ZáéíóúñÁÉÍÓÚÑ\s_,\-]+$/', $value);
    }

    public function validateIsoDate($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9]{2,4}(\-|\/)(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)(([1-9])|(0[1-9])|([1-2][0-9])|(3[0-1]))$/', $value);
    }

    public function validateUsDate($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)(([1-9])|([0-2][0-9])|(3[0-1]))(\-|\/)[0-9]{2,4}$/', $value);
    }

    public function validateSpDate($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(([1-9])|([0-2][0-9])|(3[0-1]))(\-|\/)(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)[0-9]{2,4}$/', $value);
    }

    public function validateAnyDate($attribute, $value, $parameters, $validator)
    {
        return (bool)($this->validateIsoDate($attribute, $value, $parameters, $validator) || $this->validateUsDate($attribute, $value, $parameters, $validator) || $this->validateSpDate($attribute, $value, $parameters, $validator));
    }

    public function validateTime($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/', $value);
    }

    public function validateIsoDateTime($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9]{2,4}(\-|\/)(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)(([1-9])|(0[1-9])|([1-2][0-9])|(3[0-1])) [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/', $value);
    }

    public function validateUsDateTime($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)(([1-9])|([0-2][0-9])|(3[0-1]))(\-|\/)[0-9]{2,4} [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/', $value);
    }
    
    protected function validateSpDateTime($attribute, $value)
    {
        return (bool)preg_match('/^(([1-9])|([0-2][0-9])|(3[0-1]))(\-|\/)(([1-9])|(0[1-9])|(1[0-2]))(\-|\/)[0-9]{2,4} [0-9]{1,2}:[0-9]{2}(:[0-9]{2})?( (am|pm|AM|PM))?$/', $value);
    }
    
    public function validateFullUrl($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(ftp|https|http):\/\/(www\.)?[a-zA-Z0-9\-\.]{3,}\.[a-z]{2,3}((\/|\?)[_a-zA-Z0-9#!:\.?+=&%@!\-\/]*)*$/', $value);
    }

    public function validateRelativeUrl($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(\/)?[a-zA-Z0-9_\-\.\/]+$/', $value);
    }

    public function validateUrlSegment($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_\-\.]+$/', $value);
    }

    public function validateFriendlyUrlSegments($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_\-\/]+$/', $value);
    }

    public function validateSubdomain($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_\-]+$/', $value);
    }

    public function validateCustomUrlSegment($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-Z]{1}[a-zA-Z0-9_\-]+$/', $value);
    }

    public function validateIntPercent($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^(([1-9]{1}[0-9]{0,1})|(100{1}))\s*%{0,1}$/', $value);
    }

    public function validateAlphaNumeric($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[0-9a-zA-Z]+$/', $value);
    }
    
    protected function validateBoolean($attribute, $value)
    {
        return (bool)preg_match('/^(on|off|true|false|"true"|"false"|\'true\'|\'false\'|1|0)$/', $value);
    }


    public function validateLower($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-z]+$/', $value);
    }

    public function validateVideoId($attribute, $value, $parameters, $validator)
    {
        return (bool)preg_match('/^[a-zA-Z1-9]{1}[a-zA-Z0-9_\-]+$/', $value);
    }

}