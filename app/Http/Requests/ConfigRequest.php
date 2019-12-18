<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'item_value' => 'nullable|text',
            'system'     => 'boolean',
        ];

        if ($this->isMethod('post')) {                                                      // creating new config
            $rules['item_key'] = 'required|identifier|min:3|unique:config';
        } else if ($this->isMethod('patch')) {                                              // updating config
            $rules['item_key'] = 'required|identifier|min:3|unique:config,item_key,' . $this->get('id');
        }

        return $rules;
    }

}
