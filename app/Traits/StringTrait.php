<?php

trait StringTrait
{
    public function uniqueStr($length = 6)
    {
        return substr(sha1(time()), 0, $length);
    }

    public function createSlug($subject, $separator = '-')
    {
        return str_slug($subject, $separator);
    }

    public function cleanFileName($subject)
    {
        $patterns = ['/[^a-zA-z0-9_\-\.\s]/', '/\s+/'];
        $replacements = ['', '-'];
        return strtolower(preg_replace($patterns , $replacements, $subject));
    }

    // returns an array of items with the content inside delimiters
    public function extractInside($subject, $openChar, $closeChar, $includeDelimiters = false)
    {
        // '/\[([^]].*?)\]/'   // with a dot to exclude empty space between delimiters
        $pattern = '/' . preg_quote($openChar) . '([^'. $closeChar .'].*?)' . preg_quote($closeChar) .'/';
        $matches = array();
        preg_match_all($pattern, $subject, $matches);
        return ($includeDelimiters) ? $matches[0] : ((!empty($matches[1])) ? $matches[1] : false);
    }

    // replace all contents inside delimiters with replacement
    public function replaceInside($subject, $openChar, $closeChar, $replacement, $includeDelimiters = false)
    {
        // without a dot to include empty space between delimiters
        $pattern = '/' . preg_quote($openChar) . '([^'. $closeChar .']*?)' . preg_quote($closeChar) .'/';
        if ($includeDelimiters) {
            $replacement = $openChar . $replacement . $closeChar;
        }
        return preg_replace($pattern, $replacement, $subject);
    }

    public function remove($subject, $openChar, $closeChar)
    {
        return $this->replaceInside($subject, $openChar, $closeChar, '');
    }

    public function cleanHTML($subject)
    {
        return $this->replaceInside($subject, "<", ">", '');
    }

    public function cleanHtmlAndTruncate($subject, $length, $after = '...')
    {
        $cleanedStr = $this->cleanHTML($subject);
        $truncatedStr = substr($cleanedStr, 0, $length);
        return (!empty($truncatedStr) && strlen($cleanedStr) > strlen($truncatedStr)) ? $truncatedStr . $after : $truncatedStr;
    }

    public function highlightKeywords($text, $keywords, $params = false)
    {
        $words = (!empty($params['words'])) ? $params['words'] : false;
        $enclosedTag = (!empty($params['enclosedTag'])) ? $params['enclosedTag'] : 'span';
        $class = (!empty($params['class'])) ? $params['class'] : 'highlighted-keyword';
        $cleanHtml = (!empty($params['cleanHtml'])) ? $params['cleanHtml'] : true;

        if (empty($text)) {
            return '';
        }

        // remove html tags:
        if ($cleanHtml) {
            $text = $this->cleanHTML($text);
        }

        // convert into array:
        $textArr = explode(' ', preg_replace('/\s+/', ' ', trim($text)));

        // get total words in text:
        $totalWords = count($textArr);

        // adjust amount of returned words:
        if (!$words || $words > $totalWords) {
            $words = $totalWords;
        }

        // return if there is no keyword:
        if (empty($keywords)) {
            if ($words < $totalWords) {
                $textArr = array_slice($textArr, 0, $words);
                // add ... at end:
                $textArr[] = '...';
                return implode(' ', $textArr);
            } else {
                return implode(' ', $textArr);
            }
        }

        if (!is_array($keywords)) {
            $keywords = explode(' ', preg_replace('/\s+/', ' ', trim($keywords)));
        }

        // get keywords in text:
        $keysPresent = array();
        $lowerText = strtolower($text);
        foreach ($keywords as $keyword) {
            if (strpos($lowerText, strtolower($keyword)) !== false) {
                $keysPresent[] = $keyword;
            }
        }

        // return if there is no keyword present:
        if (empty($keysPresent)) {
            if ($words < $totalWords) {
                $textArr = array_slice($textArr, 0, $words);
                // add ... at end:
                $textArr[] = '...';
                return implode(' ', $textArr);
            } else {
                return implode(' ', $textArr);
            }
        }

        if ($words < $totalWords) {
            // get index of first occurrence of a keyword
            for ($i = 0; $i < $totalWords; $i++) {
                if (in_array($textArr[$i], $keysPresent)) {
                    break;
                }
            }

            $halfWords = floor($words / 2);

            if ($i + $halfWords > $totalWords) {     // It is nearer to end. Take end minus halfWords
                $textArr = array_slice($textArr, ($totalWords - $words), $words);
                // add ... at start:
                array_splice($textArr, 0, 0, '...');
            } else if ($i - $halfWords < 0) {        // i is nearer to beginning. Start from beginning
                $textArr = array_slice($textArr, 0, $words);
                // add ... at end:
                $textArr[] = '...';
            } else {
                $textArr = array_slice($textArr, ($i - $halfWords), $words);
                // add ... at start:
                array_splice($textArr, 0, 0, '...');
                // add ... at end:
                $textArr[] = '...';
            }
        }

        $text = implode(' ', $textArr);

        // enclose keywords with html tag
        $patterns = array();
        $replacements = array();

        foreach ($keysPresent as $keyword) {
            $same = $keyword;
            $lower = strtolower($keyword);
            $upper = strtoupper($keyword);
            $capitalized = ucfirst($lower);

            $patterns[] = "/$same/";
            $patterns[] = "/$lower/";
            $patterns[] = "/$upper/";
            $patterns[] = "/$capitalized/";

            $replacements[] = "<$enclosedTag class='$class'>$same</$enclosedTag>";
            $replacements[] = "<$enclosedTag class='$class'>$lower</$enclosedTag>";
            $replacements[] = "<$enclosedTag class='$class'>$upper</$enclosedTag>";
            $replacements[] = "<$enclosedTag class='$class'>$capitalized</$enclosedTag>";
        }

        return preg_replace($patterns, $replacements, $text);
    }

}

