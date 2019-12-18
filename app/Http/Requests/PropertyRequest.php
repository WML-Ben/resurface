<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class PropertyRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'owner_id'      => 'nullable|zeroOrPositive',
            'company_id'    => 'nullable|zeroOrPositive',
            'manager_id'    => 'nullable|zeroOrPositive',
            'phone'         => 'nullable|phone',
            'address'       => 'nullable|address',
            'address_2'     => 'nullable|address',
            'city'          => 'nullable|location',
            'zipcode'       => 'nullable|postalCode',
            'state_id'      => 'nullable|zeroOrPositive',
            'country_id'    => 'nullable|zeroOrPositive',
            'parcel_number' => 'nullable|plainText',
            'comment'       => 'nullable|plainText',
            'qualified'     => 'boolean',
            'disabled'      => 'boolean',
        ];

        if ($this->isMethod('post')) {                                                        // creating new property
            $rules['name'] = 'required|plainText|unique:properties';
            $rules['email'] = 'nullable|email|unique:properties';
        } else if ($this->isMethod('patch')) {                                                // updating property
            $rules['name'] = 'required|plainText|unique:properties,name,' . $this->get('id');
            $rules['email'] = 'nullable|email|unique:properties,email,' . $this->get('id');
        }

        return $rules;
    }

}