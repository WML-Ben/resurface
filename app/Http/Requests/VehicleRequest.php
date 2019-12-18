<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class VehicleRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'type_id'      => 'required|positive',
            'location_id'  => 'required|positive',
            'description'  => 'nullable|plainText',
            'vin_number'   => 'nullable|plainText',
            'purchased_at' => 'nullable|usDate',
            'disabled'     => 'nullable|boolean',
        ];

        if ($this->isMethod('post')) {                                                        // creating new vehicle
            $rules['name'] = 'required|plainText|unique:vehicles';
        } else if ($this->isMethod('patch')) {                                                // updating vehicle
            $rules['name'] = 'required|plainText|unique:vehicles,name,' . $this->get('id');
        }

        return $rules;
    }

}