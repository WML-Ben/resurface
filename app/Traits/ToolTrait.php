<?php

trait ToolTrait
{
    /**
     *  return array
     *       link:                                  url to picture details
     *       likes->count
     *       caption->text
     *       images->thumbnail->url                 (150x150)
     *       images->low_resolution->url            (320x320)
     *       images->lstandard_resolution->url      (640x640)
     */
    public function getInstagramFeed($instagramId)
    {
        if (!empty($instagramId)) {
            if (strpos($instagramId, '/') !== false) {
                $segments = explode('/', $instagramId);

                $instagramId = $segments[count($segments) - 1];
            }

            $response = @file_get_contents('http://instagram.com/'.str_replace('@', '', $instagramId).'/media');

            if (!empty($response)) {
                $result = json_decode($response);

                if (!empty($result) && $result->status == 'ok') {
                    return $result->items;
                }
            }
        }

        return null;
    }

    public function getInstagramImages($instagramId)
    {
        if (!empty($instagramId)) {
            if (strpos($instagramId, '/') !== false) {
                $segments = explode('/', $instagramId);

                $instagramId = $segments[count($segments) - 1];
            }

            $images = array();

            $response = @file_get_contents('http://instagram.com/'.str_replace('@', '', $instagramId).'/media');

            if (!empty($response)) {
                $result = json_decode($response);

                if (!empty($result) && $result->status == 'ok') {
                    foreach ($result->items as $item) {
                        $images[] = $item->images->low_resolution->url;
                    }
                }

                return $images;
            }
        }

        return null;
    }

    public function buildAddress($address, $city = '', $state = '', $zip = '', $country = '', $separator = '<br>', $address2 = '')
    {
        $result = [];
        $line1 = !empty($address) ? [$address] : [];
        if (!empty($address2)) {
            $line1[] = $address2;
        }
        if (count($line1)) {
            $result[] = count($line1) > 1 ? implode(', ', $line1) : $line1[0];
        }

        $line2 = [];
        if (!empty($city)) {
            $line2[] = $city;
        }
        $stateZip = [];
        if (!empty($state)) {
            $stateZip[] = $state;
        }
        if (!empty($zip)) {
            $stateZip[] = $zip;
        }
        if (!empty($stateZip)) {
            $line2[] = implode(' ', $stateZip);
        }
        if (!empty($line2)) {
            $result[] = implode(', ', $line2);
        }
        if (!empty($country)) {
            $result[] = $country;
        }
        return implode($separator, $result);
    }

    function percentSearch($needle, $haystack, $maxPercent = 0, $exactCheck = false)
    {
        if (empty($needle) || empty($haystack)) {
            return false;
        }
        if (is_string($haystack)) {
            $haystack = (array($haystack));
        }
        $patterns = array(
            '/(Ã¡|á|&aacute;|à|&agrave;)/',
            '/(Á|&Aacute;|À|&Agrave;|Ã€)/',
            '/(Ã©|é|&eacute;|è|&egrave;|Ã¨)/',
            '/(Ã‰|É|&Eacute;|È|&Egrave;|Ãˆ)/',
            '/(Ã­|í|&iacute;|ì|&igrave;|Ã¬)/',
            '/(Í|&Iacute;|Ì|&Igrave;|ÃŒ)/',
            '/(Ã³|ó|&oacute;|ò|&ograve;|Ã²)/',
            '/(Ã“|Ó|&Oacute;|Ò|&Ograve;|Ã’)/',
            '/(Ãº|ú|&uacute;|ù|&ugrave;|Ã¹)/',
            '/(Ãš|Ú|&Uacute;|Ù|&Ugrave;|Ã™)/',
            '/(Ã±|ñ|&ntilde;)/',
            '/(Ã‘|Ñ|&Ntilde;)/',
            '/\s+/',
        );
        $replacements = array(
            'a',
            'A',
            'e',
            'E',
            'i',
            'I',
            'o',
            'O',
            'u',
            'U',
            'n',
            'N',
            ' ',
        );
        $needleLength = strlen($needle);
        $deviation = -1;
        $closestMatch = '';
        if (!$exactCheck) {
            $needle = trim(strtolower(preg_replace($patterns , $replacements,($needle))));
        }
        $percent = ($maxPercent < 0 || $maxPercent > 100) ? 1 : ($maxPercent * $needleLength / 100);
        foreach ($haystack as $item) {
            $cleanItem = ($exactCheck) ? $item : trim(strtolower(preg_replace($patterns, $replacements, $item)));
            $lev = levenshtein($needle, $cleanItem);
            if ($lev <= $deviation || $deviation < 0) {
                $closestMatch = $item;
                $deviation = $lev;
            }
            if ($deviation == 0) {
                break;
            }
        }
        return array(
            'deviation'    => $deviation,
            'closestMatch' => $closestMatch,
            'index'        => array_search($item, $haystack),
            'accuracy'     => ($deviation < 0) ? 0 : round(abs($needleLength - $deviation) * 100 / $needleLength, 2), // percent of deviation compared to needle length. 100% => 0 deviation
        );
    }

    function FormatCurrencyOutput($value, $showDecimals = true, $showDollarSign = true, $showThousandSeparator = true)
    {
        if (empty($value)) {
            $value = 0.00;
        }
        $value = sprintf("%0.2f", $value);
        $origArr = explode('.', $value);
        $intPortion = $origArr[0];
        $charArr = str_split($intPortion);
        $invArr = array_reverse($charArr);
        $invStr = implode('', $invArr);
        if ($showThousandSeparator) {
            $chunkInvArr = str_split($invStr, 3);
            $invStr = implode(',', $chunkInvArr);
        }
        $invCharArr = str_split($invStr);
        $charArr = array_reverse($invCharArr);
        $newIntPortion = implode('', $charArr);
        $newIntPortion = str_replace('-,', '-', $newIntPortion);
        if ($showDecimals) {
            $newIntPortion .= '.' . $origArr[1];
        }
        if ($showDollarSign) {
            $newIntPortion = '$' . $newIntPortion;
        }
        return $newIntPortion;
    }

    public function GenerateDigitsCode()
    {
        require_once (__DIR__ . '/BigInteger.php');

        $pat = array('0', '1', '2','3','4','5','6','7','8','9','A','B','C','D','E','F','G',
            'H','I','J','K','L' ,'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');   // 36 total items (10 digits + 26 letters)
        $pot = array(
            '1',
            '36',
            '1296',
            '46656',
            '1679616',
            '60466176',
            '2176782336',
            '78364164096',
            '2821109907456',
            '101559956668416',
            '3656158440062976',
            '131621703842267136',
            '4738381338321616896',
            '170581728179578208256', // 36 ^ 13
        );
        $val = array();

        list($mSec, $sec) = explode(" ", microtime());
        $mSecDec = str_replace('0.', '', $mSec); // obtener los decimales (despues del punto)
        $num = preg_replace('/0{2}$/', '', $sec . $mSecDec);

        $totDigits = strlen($num);

        // get the pot index where item is the closets smaller. Start with the second element (first one is 1, $num has to be greater)
        $n = 1;
        while ($n < count($pot)) {
            if ($pot[$n] > $num) {
                $n--;
                break;
            } else {
                $n++;
            }
        }
        for ($i = $n; $i >= 0; $i--) {
            $a = new Math_BigInteger($num);
            $b = new Math_BigInteger($pot[$i]);
            list($quotient, $residue) = $a->divide($b);
            $ent = (integer)$quotient->toString();
            $num = (float)$residue->toString();
            $val[] = $pat[$ent];
        }
        return implode($val);
    }

    public function GenerateAlphaNumericString($length = 10)
    {
        $pat = array('0', '1', '2','3','4','5','6','7','8','9',
            'a','b','c','d','e','f','g','h','i','j','k','l' ,'m','n','o','p','q','r','s','t','u','v','w','x','y','z',
            'A','B','C','D','E','F','G','H','I','J','K','L' ,'M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
            '_');
        if (strlen($length) > 100) {
            $length = 100;
        }
        $s = '';
        for ($i = 0; $i < $length; $i++) {
            $max = count($pat);
            $index = rand(0, $max-1);
            $s .= $pat[$index];
        }
        return $s;
    }

    function FormatNumber($str, $dec = true)
    {
        $str = sprintf("%0.2f", $str);
        $origArr = explode('.', $str);
        $intPortion = $origArr[0];
        $charArr = str_split($intPortion);
        $invArr = array_reverse($charArr);
        $invStr = implode('', $invArr);
        $chunkInvArr = str_split($invStr, 3);
        $invStr = implode(',', $chunkInvArr);
        $invCharArr = str_split($invStr);
        $charArr = array_reverse($invCharArr);
        $newIntPortion = implode('', $charArr);
        $newIntPortion = str_replace('-,', '-', $newIntPortion);
        if ($dec) {
            $newIntPortion .= '.' . $origArr[1];
        }
        return $newIntPortion;
    }

}
