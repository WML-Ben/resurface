<?php namespace App;

use Illuminate\Database\Eloquent\Model;

use DateTime;
use StringTrait;
use ToolTrait;
use \Laravelrus\LocalizedCarbon\Traits\LocalizedEloquentTrait;

class BaseModel extends Model
{
    use StringTrait, ToolTrait, LocalizedEloquentTrait;

    /** scopes */
    
    public function scopeExcept($query, $excludedFields = [])
    {
        $allFields = \Schema::getColumnListing($this->getTable());

        if (!empty($excludedFields) && !is_array($excludedFields)) {
            $excludedFields = explode(',', preg_replace('/\s+/', '', $excludedFields));
        }

        return $query->select(array_diff($allFields,  $this->hidden, $excludedFields));
    }

    public function scopeActive($query)
    {
        return $query->where($this->getTable().'.disabled', 0);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('d_sort');
    }

    public function scopeRandom($query)
    {
        return $query->orderByRaw("RAND()");
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('created_at', 'DESC');
    }

    /** Mutators and Accessors */

    public function setCountryIdAttribute($value)
    {
        $this->attributes['country_id'] = empty($value) ? 231 : $value;
    }

    public function setStateIdAttribute($value)
    {
        $this->attributes['state_id'] = empty($value) ? 3930 : $value;
    }

    public function setBillingCountryIdAttribute($value)
    {
        $this->attributes['billing_country_id'] = empty($value) ? 231 : $value;
    }

    public function setBillingStateIdAttribute($value)
    {
        $this->attributes['billing_state_id'] = empty($value) ? 3930 : $value;
    }

    public function getFullLocationAttribute()
    {
        return $this->buildAddress($this->address, $this->city, !empty($this->state) && !empty($this->state->short_name) ? $this->state->short_name : null, $this->zipcode, !empty($this->country) && !empty($this->country->name) ? $this->country->name : null, '<br>');
    }

    public function getShortLocationAttribute()
    {
        return $this->buildAddress($this->address, $this->city, !empty($this->state) && !empty($this->state->short_name) ? $this->state->short_name : null, $this->zipcode, null, '<br>');
    }

    public function getShortLocationOneLineAttribute()
    {
        return $this->buildAddress($this->address, $this->city, !empty($this->state) && !empty($this->state->short_name) ? $this->state->short_name : null, $this->zipcode, null, ', ');
    }

    public function getSeoAttribute()
    {
        $config = session()->get('config');

        $seo = [
            'pageTitle'   => (!empty($this->attributes['seo_title_'.$this->lang])) ? $this->attributes['seo_title_'.$this->lang] : (!empty($config['pageTitle_' . $this->lang]) ? $config['pageTitle_' . $this->lang] : 'Cisneros '),
            'description' => (!empty($this->attributes['seo_description_'.$this->lang])) ? $this->attributes['seo_description_'.$this->lang] : (!empty($config['seoDefaultDescription_' . $this->lang]) ? $config['seoDefaultDescription_' . $this->lang] : 'cisneros'),
            'keywords'    => (!empty($this->attributes['seo_keywords_'.$this->lang])) ? $this->attributes['seo_keywords_'.$this->lang] : (!empty($config['seoDefaultKeywords_' . $this->lang]) ? $config['seoDefaultKeywords_' . $this->lang] : 'cisneros'),
        ];

        return $seo;
    }

    public function getHtmlCreatedAtAttribute()
    {
        return !empty($this->created_at) ? $this->created_at->format('M. d, Y') . '<br>' . $this->created_at->format('g:i A') : null;
    }

    public function getHtmlUpdatedAtAttribute()
    {
        return !empty($this->updated_at) ? $this->updated_at->format('M. d, Y') . '<br>' . $this->updated_at->format('g:i A') : null;
    }

    public function getHtmlDSortAttribute()
    {
        return ($this->d_sort < 1000) ? $this->d_sort : '';
    }

    /** Methods */
    
    public function getMaxDSort()
    {
        return $this->where('d_sort', '<', 1000)->max('d_sort');
    }

    public static function getMaxIndex()
    {
        return self::where('d_sort', '<', 1000)->max('d_sort');
    }

    static public function mergeAssoc($arr1, $arr2)
    {
        if (!is_array($arr1)) {
            $arr1 = [];
        }
        if (!is_array($arr2)) {
            $arr2 = [];
        }

        $keys1 = array_keys($arr1);
        $keys2 = array_keys($arr2);
        $keys = array_merge($keys1, $keys2);
        $vals1 = array_values($arr1);
        $vals2 = array_values($arr2);
        $vals = array_merge($vals1, $vals2);
        $ret = [];
        foreach ($keys as $index=>$key) {
			$ret[$key] = $vals[$index];
        }

        return $ret;
    }

    // example of combobox that keeps the numeric values when adding default like [null => '']

    static public function namesCB($default = [])
    {
        return $default + self::pluck('name', 'id')->toArray();
    }


}
