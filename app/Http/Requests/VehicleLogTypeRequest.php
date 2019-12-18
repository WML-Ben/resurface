<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class VehicleLogTypeRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'd_sort'           => 'positive',
        ];

        if ($this->isMethod('post')) {                                                        // creating new vehicle log types
            $rules['name'] = 'required|plainText|unique:vehicle_log_types';
        } else if ($this->isMethod('patch')) {                                                // updating vehicle log types
            $rules['name'] = 'required|plainText|unique:vehicle_log_types,name,' . $this->get('id');
        }

        return $rules;
    }

}