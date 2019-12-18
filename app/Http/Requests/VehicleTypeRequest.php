<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class VehicleTypeRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'description' => 'nullable|plainText',
            'rate'        => 'required|currency',
        ];

        if ($this->isMethod('post')) {                                                        // creating new vehicle type
            $rules['name'] = 'required|plainText|unique:vehicle_types';
        } else if ($this->isMethod('patch')) {                                                // updating vehicle type
            $rules['name'] = 'required|plainText|unique:vehicle_types,name,' . $this->get('id');
        }

        return $rules;
    }

}