<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class AgePeriodRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'initial_day'      => 'nullable|positive|required_if:final_day,NULL',
            'final_day'        => 'nullable|positive|required_if:initial_day,NULL',
            'icon_class'       => 'nullable|plainText',
            'icon_color'       => 'nullable|plainText',
            'text_color'       => 'nullable|plainText',
            'background_color' => 'nullable|plainText',
            'd_sort'           => 'positive',
        ];

        if ($this->isMethod('post')) {                                                        // creating new age_period
            $rules['name'] = 'required|plainText|unique:age_periods';
        } else if ($this->isMethod('patch')) {                                                // updating age_period
            $rules['name'] = 'required|plainText|unique:age_periods,name,' . $this->get('id');
        }

        return $rules;
    }

}