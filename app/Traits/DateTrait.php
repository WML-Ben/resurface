<?php

trait DateTrait
{
    public function date($date, $format = "Y-m-d", $sp = false)
    {
        if (empty($date) || in_array(substr($date, 0, 1), ['-', '0'])) {
            return '';
        }
        $d = new DateTime($date);
        $res = $d->format($format);
        return (!$sp) ? $res : $this->MonthToSpanish($res);
    }

    function MonthToSpanish($str, $three = false)
    {
        $monthArr = array(
            '/January/', '/February/', '/March/',
            '/April/', '/May/', '/June/', '/July/',
            '/August/', '/September/', '/October/', '/November/', '/December/',
            '/Jan/', '/Apr/', '/Aug/', '/Dec/',
        );
        $mesArr = array(
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
            'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
            'Ene', 'Abr', 'Ago', 'Dic',
        );
        $res = preg_replace($monthArr, $mesArr, $str);
        if ($three) {
            $res = str_replace('Mayo', 'May', $res);
        }
        return $res;
    }

    function DateTimeToDate($dt, $showZeros = false)
    {
        $res = false;
        $p = strpos($dt, " ");
        if ($p) {
            $res = substr($dt, 0, $p);
            if ((!$showZeros) && ($res == "0000-00-00")) {
                $res = "";
            }
        }
        return $res;
    }

    function DateTimeToUSDate($dt, $showZeros = false)
    {
        $date = self::DateTimeToDate($dt, $showZeros);
        if ($date != '') {
            $dateArr = explode('-', $date);
            $year = $dateArr[0];
            $month = $dateArr[1];
            $day = $dateArr[2];
        }
        $res = ($date != '') ? $month . '/' . $day . '/' . $year : $date;
        return $res;
    }

    function DateTimeToTime($dt, $showZeros = false)
    {
        $res = false;
        $p = strpos($dt, " ");
        if ($p) {
            $res = substr($dt, $p + 1);
            if ((!$showZeros) && ($res == "00:00:00")) {
                $res = "";
            }
        }
        return $res;
    }

    function ifDateTimeSet($dt)
    {
        if ((!isset($dt)) || ($dt == "") || ($dt == "0000-00-00 00:00:00")) {
            $res = false;
        } else {
            $res = true;
        }
        return $res;
    }

    function Now()
    {
        $now = getdate();

        $mon = str_pad($now['mon'], 2, "0", STR_PAD_LEFT);
        $day = str_pad($now['mday'], 2, "0", STR_PAD_LEFT);

        $hours = str_pad($now['hours'], 2, "0", STR_PAD_LEFT);
        $minutes = str_pad($now['minutes'], 2, "0", STR_PAD_LEFT);
        $seconds = str_pad($now['seconds'], 2, "0", STR_PAD_LEFT);

        return $now['year'] . "-" . $mon . "-" . $day . " " . $hours . ":" . $minutes . ":" . $seconds;
    }

    function GetDaysDiff($date1, $date2, $signed = false)
    {
        if (!$signed) {
            $dateDiff = abs(strtotime($date1) - strtotime($date2));
        } else {
            $dateDiff = strtotime($date1) - strtotime($date2);
        }
        return floor($dateDiff / (60 * 60 * 24));
    }

    function GetDiffInDHM($date1, $date2, $format = 'd')
    {
        $diffArr = array();
        if ($date1 != $date2) {
            if ($format == 'd') {
                $date1 = strtotime($date1);
                $date2 = strtotime($date2);
            }

            $dateDiff = abs($date2 - $date1);

            $fullDays    = floor($dateDiff / (60 * 60 * 24));
            $fullHours   = floor(($dateDiff - ($fullDays * 60 * 60 * 24)) / (60 * 60));
            $fullMinutes = floor(($dateDiff - ($fullDays * 60 * 60 * 24) - ($fullHours * 60 * 60)) / 60);

            $diffArr['days'] = $fullDays;
            $diffArr['hours'] = $fullHours;
            $diffArr['minutes'] = $fullMinutes;
        }
        return $diffArr;
    }

    function GetDiffInYMD($date1, $date2, $format = 'd')
    {
        $diffArr = array();
        if ($date1 != $date2) {
            if ($format == 'd') {
                $date1 = strtotime($date1);
                $date2 = strtotime($date2);
            }

            $diff = abs($date2 - $date1);

            $years = floor($diff / (365 * 60 * 60 * 24));
            $monthArr = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
            $days = floor(($diff - $years * 365 * 60 * 60 * 24 - $monthArr * 30 * 60 * 60 * 24)/ (60 * 60 * 24));

            $diffArr['years'] = $years;
            $diffArr['months'] = $monthArr;
            $diffArr['days'] = $days;
        }
        return $diffArr;
    }

    function DiffInDHMToStr($date1, $date2, $format = 'd')
    {
        $diff = self::GetDiffInDHM($date1, $date2, $format);

        $timeStr = '';
        $days = $diff['days'];
        if ($days) {
            $timeStr = $days . 'd';
        }
        $hours = $diff['hours'];
        if ($hours) {
            if (empty($timeStr)) {
                $timeStr = $hours . 'h';
            } else {
                $timeStr .= ',' . $hours . 'h';
            }
        }
        $minutes = $diff['minutes'];
        if ($minutes) {
            if (empty($timeStr)) {
                $timeStr = $minutes . 'm';
            } else {
                $timeStr .= ',' . $minutes . 'm';
            }
        }
        return $timeStr;
    }

    function AddDays($startFrom, $days, $format = 'd')
    {
        if ($format != 'd')
            $startFrom = strtotime($startFrom);
        $dia = date('j', $startFrom);
        $mes = date('n', $startFrom);
        $ano = date('Y', $startFrom);

        $midnight = mktime(23, 59, 59, $mes, $dia, $ano);

        return abs($midnight + ($days * 24 * 60 * 60));
    }

    public function dateToUS($date)         //  $date in format:  'Tue Dec 10 2013 00:00:00 GMT-0500 (Eastern Standard Time)';
    {
        $date = preg_replace('/\s+/', ' ', $date);
        $arr = explode(' ', $date);
        $arr = array_slice($arr, 0, 4);
        $date = implode(' ', $arr);
        $d = new DateTime($date);
        return $d->format("m/d/Y");
    }

    public function dateToMySQL($date)      //  $date in format:  'Tue Dec 10 2013 00:00:00 GMT-0500 (Eastern Standard Time)';
    {
        $date = preg_replace('/\s+/', ' ', $date);
        $arr = explode(' ', $date);
        $arr = array_slice($arr, 0, 4);
        $date = implode(' ', $arr);
        $d = new DateTime($date);
        return $d->format('Y-m-d');
    }

    public function MySQLToUS($date)
    {
        if (!empty($date) && count($dateArr = explode('-', $date)) == 3) {
            return $dateArr[1] . '/' . $dateArr[2] . '/' . $dateArr[0];
        } else {
            return $date;
        }
    }

    public function USToMySQL($date)
    {
        if (!empty($date) && count($dateArr = explode('/', $date)) == 3) {
            return $dateArr[2] . '-' . $dateArr[0] . '-' . $dateArr[1];
        } else {
            return $date;
        }
    }

    public function SPToMySQL($date)  // dd/mm/yyyy or dd-mm-yyyy
    {
        $date = str_replace('/', '-', $date);
        if (!empty($date) && count($dateArr = explode('-', $date)) == 3) {
            return $dateArr[2] . '-' . $dateArr[1] . '-' . $dateArr[0];
        } else {
            return $date;
        }
    }

    public function SPToUS($date)  // dd/mm/yyyy or dd-mm-yyyy
    {
        $date = str_replace('/', '-', $date);
        if (!empty($date) && count($dateArr = explode('-', $date)) == 3) {
            return $dateArr[1] . '/' . $dateArr[0] . '/' . $dateArr[2];
        } else {
            return $date;
        }
    }

    public function USToSP($date)  // mm/dd/yyyy or mm-dd-yyyy
    {
        $date = str_replace('/', '-', $date);
        if (!empty($date) && count($dateArr = explode('-', $date)) == 3) {
            return $dateArr[1] . '-' . $dateArr[0] . '-' . $dateArr[2];
        } else {
            return $date;
        }
    }

    public function timeAmPmToH($time)
    {
        if (!empty($time) && count($timeArr = explode(' ', preg_replace('/\s+/', ' ', $time))) == 2 && (strtolower($timeArr[1]) == 'am' || strtolower($timeArr[1]) == 'pm')) {
            $tArr = explode(':', $timeArr[0]);
            if (strtolower($timeArr[1]) == 'am') {
                if ($tArr[0] == '12') {
                    return '0:' . $tArr[1];
                } else {
                    return $timeArr[0];
                }
            } else {
                // pm:
                if ($tArr[0] == '12') {
                    return $timeArr[0];
                } else {
                    return (string)(((integer)$tArr[0] + 12)) . ':' . $tArr[1];
                }
            }
        } else {
            return $time;
        }
    }

    public function timeHToAmPm($time)
    {
        if (!empty($time) && count($timeArr = explode(':', $time)) >= 2) {
            if ($timeArr[0] > 11) {
                if ($timeArr[0] > 12) {
                    $timeArr[0] -= 12;
                }
                return preg_replace('/^0/', '', $timeArr[0]) . ':' . $timeArr[1] . ' pm';
            } else {
                if ($timeArr[0] == 0) {
                    $timeArr[0] = 12;
                }
                return preg_replace('/^0/', '', $timeArr[0]) . ':' . $timeArr[1] . ' am';
            }
        } else {
            return $time;
        }
    }

    public function roundMinutes($date)
    {
        $d = new DateTime($date);
        $seconds = $d->format('s');
        return ($seconds > 30) ? $d->add(new DateInterval('PT'. (60 - $seconds) .'S'))->format('Y-m-d H:i:s') : $d->sub(new DateInterval('PT'. ($seconds) .'S'))->format('Y-m-d H:i:s');
    }

    // end always in x:59
    public function roundEndMinutes($date)
    {
        $d = new DateTime($date);
        $seconds = $d->format('s');
        return ($seconds > 30) ? $d->add(new DateInterval('PT'. (59 - $seconds) .'S'))->format('Y-m-d H:i:s') : $d->sub(new DateInterval('PT'. ($seconds + 1) .'S'))->format('Y-m-d H:i:s');
    }

    // end always in x:00
    public function roundStartMinutes($date)
    {
        return $this->roundMinutes($date);
    }
}
