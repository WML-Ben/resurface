<?php
/**  Version 2.4 - 2018-04-09 - Use urldecode at the begining to avoid issue on https (| is changed to %257C)
 *
 * v2.3 - 2017-12-20 - Update encSortable for new syntax on sortable array declaration (this model table name at the begining)
 * v2.2 - 2017-09-18 - Updated link function to allow route array as third parameter
 * v2.1 - 2017-08-11 - Added encSortable scope (from noticiero). Still uses old version of relationships declaration
 * v2.0 - 2017-08-11 - Added bridge table and reorder tables as maintable.foreignkeyfield[|bridgetable-tomainfield-torelatedfield]relatedtable.field[.mainkeyfield]
 *
 */

trait SortableTrait
{
    public abstract function sortableColumns();
    
    private function _buildQuery($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc', $enc = false)
    {
        $f = urldecode(\Input::get('f'));
        
        if ($f && $enc) {
            $f =  self::decrypt($f);
        }

        if ($f && \Input::has('o') && in_array($f, $this->sortableColumns())) {
            if (strpos($f, '|')) {
                $segments = explode('|', $f);
                if (count($segments) == 2) {

                    list($main, $related) = $segments;

                    list($mainTable, $mainField) = explode('.', $main);

                    if (substr_count($related, '.') == 2) {
                        list($relatedTable, $relatedField, $relatedKey) = explode('.', $related);
                    } else {
                        list($relatedTable, $relatedField) = explode('.', $related);
                        $relatedKey = 'id';
                    }

                    //dd('JOIN '.$relatedTable.' ON '.$relatedTable . '.' . $relatedKey .' = '. $mainTable .'.' . $mainField.' ORDER BY '.$relatedTable . '.' . $relatedField.' ' . \Input::get('o'));

                    return $query->leftJoin($relatedTable, $relatedTable . '.' . $relatedKey, '=', $mainTable .'.' . $mainField)
                        ->orderBy($relatedTable . '.' . $relatedField, \Input::get('o'))
                        ->select([$mainTable.'.*']);

                } else if (count($segments) == 3) {
                    list($main, $bridge, $related) = $segments;

                    if (substr_count($related, '.') == 2) {
                        list($relatedTable, $relatedField, $relatedKey) = explode('.', $related);
                    } else {
                        list($relatedTable, $relatedField) = explode('.', $related);
                        $relatedKey = 'id';
                    }

                    list($mainTable, $mainField) = explode('.', $main);

                    list($bridgeTable, $bridgeToMain, $bridgeToRelated) = explode('-', $bridge);

                    return $query->leftJoin($bridgeTable, $bridgeTable . '.' . $bridgeToMain, '=', $mainTable .'.' . $mainField)
                        ->leftJoin($relatedTable, $relatedTable . '.' . $relatedKey, '=', $bridgeTable .'.' . $bridgeToRelated)
                        ->orderBy($relatedTable . '.' . $relatedField, \Input::get('o'))
                        ->select([$mainTable.'.*']);
                }
            } else {
                return $query->orderBy($f, \Input::get('o'));
            }
        } else if ($defaultFieldOrder) {
            $query->orderBy($defaultFieldOrder, $defaultTypeOrder);
        } else {
            return $query;
        }
    }

    public function scopeSortable($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc')
    {
        return $this->_buildQuery($query, $defaultFieldOrder, $defaultTypeOrder, false);
    }
    
    // This still uses old syntax! on sortable array declaration (this model table name at the end)
    
    public function scopeEncSortable($query, $defaultFieldOrder = false, $defaultTypeOrder = 'asc')
    {
        return $this->_buildQuery($query, $defaultFieldOrder, $defaultTypeOrder, true);
    }

    /** for table column headers */
    public static function link($col, $title = null, $routeParams = [])
    {
        $f = urldecode(\Input::get('f'));
        
        if (is_null($title)) {
            $title = str_replace('_', ' ', $col);
            $title = ucfirst($title);
        }

        $indicator = ($f && urldecode($f) == $col) ? (\Input::get('o') === 'asc' ? '&uarr;' : '&darr;') : null;
        $parameters = array_merge($routeParams, \Input::get(), ['f' => $col, 'o' => (\Input::get('o') === 'asc' ? 'desc' : 'asc')]);

        return link_to_route(\Route::currentRouteName(), "$title $indicator", $parameters);
    }

    // must define ENCRYPTION_KEY      ex: ENCRYPTION_KEY=bcb04b7e103a0cd8b54763051cef08bc55abe029fdebae5e1d417e2ffb2a00a3
    
    public static function encLink($col, $title = null)
    {
        $f = urldecode(\Input::get('f'));
        
        if (is_null($title)) {
            $title = str_replace('_', ' ', $col);
            $title = ucfirst($title);
        }

        if (\Input::has('e') && \Input::get('e') == 1) {
            $indicator = ($f && self::decrypt(urldecode($f)) == $col) ? (\Input::get('o') === 'asc' ? '&uarr;' : '&darr;') : null;
        } else {
            $indicator = ($f && $f == $col) ? (\Input::get('o') === 'asc' ? '&uarr;' : '&darr;') : null;
        }

        $parameters = array_merge(\Input::get(), array('e' => 1, 'f' => self::encrypt($col), 'o' => (\Input::get('o') === 'asc' ? 'desc' : 'asc')));

        return link_to_route(Route::currentRouteName(), "$title $indicator", $parameters);
    }

    /** toggle button */
    public static function orderLink($mainFieldName, $params)
    {
        $f = urldecode(\Input::get('f'));
        
        if (!is_null($params['text'])) {
            $text = $params['text'];
        } else {
            $text = str_replace('_', ' ', $mainFieldName);
            $text = ucfirst($text);
        }
        $class = (!empty($params['class'])) ? ' class="' . $params['class'] . '"' : '';
        $title = (!empty($params['title'])) ? ' title="' . $params['title']  . '"' : '';

        $indicator =  $f == $mainFieldName ? (\Input::get('o') === 'asc' ? ' <i class="fa fa-sort-amount-asc ml5 m_right_0"></i>' : ' <i class="fa fa-sort-amount-desc ml5 m_right_0"></i>') : null;
        $parameters = array_merge(\Input::get(), array('f' => $mainFieldName, 'o' => (\Input::get('o') === 'asc' ? 'desc' : 'asc')));

        return '<a href="' . route(\Route::currentRouteName(), $parameters) . '"' . $class . $title . '>' . $text . $indicator . '</a>';
    }

    /** for one direction  buttons */
    public static function fixedOrderLink($col, $direction, $title = null)
    {
        if (is_null($title)) {
            $title = '&nbsp;';
        }
        $direction = strtolower($direction);
        if ($direction != 'asc' && $direction != 'desc') {
            $direction = 'asc';
        }
        $parameters = array_merge(\Input::get(), array('f' => $col, 'o' => $direction));

        return link_to_route(\Route::currentRouteName(), "$title", $parameters);
    }

    public static function fixedOrderRoute($col, $direction = 'asc')
    {
        $direction = strtolower($direction);
        if ($direction != 'asc' && $direction != 'desc') {
            $direction = 'asc';
        }
        $parameters = array_merge(\Input::get(), array('f' => $col, 'o' => $direction));

        return route(\Route::currentRouteName(), $parameters);
    }
    
    /** encryption / decryption functions */
    
    private static function encrypt($plaintext)
    {
        // http://php.net/manual/en/function.mcrypt-encrypt.php
    
        $key = pack('H*', env('ENCRYPTION_KEY'));
    
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
        $ciphertext = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $plaintext, MCRYPT_MODE_CBC, $iv);
        $ciphertext = $iv . $ciphertext;
    
        return urlencode(base64_encode($ciphertext));
    }
    
    private static function decrypt($ciphertext_base64)
    {
        $key = pack('H*', env('ENCRYPTION_KEY'));
    
        $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC);
        // $ciphertext_dec = base64_decode(urldecode($ciphertext_base64));
        $ciphertext_dec = base64_decode($ciphertext_base64);
        $iv_dec = substr($ciphertext_dec, 0, $iv_size);
        $ciphertext_dec = substr($ciphertext_dec, $iv_size);

        return preg_replace('/\x00/', '', mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $ciphertext_dec, MCRYPT_MODE_CBC, $iv_dec));
    }

}