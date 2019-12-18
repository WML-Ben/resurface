<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompanyRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'category_id'        => 'required|positive',
            'phone'              => 'nullable|phone',
            'alt_phone'          => 'nullable|phone',
            'address'            => 'required|address',
            'address_2'          => 'nullable|nullable|address',
            'city'               => 'required|location',
            'zipcode'            => 'required|postalCode',
            'state_id'           => 'nullable|zeroOrPositive',
            'country_id'         => 'nullable|zeroOrPositive',
            'billing_address'    => 'nullable|address',
            'billing_address_2'  => 'nullable|address',
            'billing_city'       => 'nullable|location',
            'billing_zipcode'    => 'nullable|postalCode',
            'billing_state_id'   => 'nullable|zeroOrPositive',
            'billing_country_id' => 'nullable|zeroOrPositive',
            'comment'            => 'nullable|plainText',
            'qualified'          => 'boolean',
            'disabled'           => 'boolean',
        ];

        if ($this->isMethod('post')) {                                                        // creating new company
            $rules['name'] = 'required|plainText|unique:companies';
            $rules['email'] = 'nullable|email|unique:companies';
            $rules['alt_email'] = 'nullable|email|unique:companies';
        } else if ($this->isMethod('patch')) {                                                // updating company
            $rules['name'] = 'required|plainText|unique:companies,name,' . $this->get('id');
            $rules['email'] = 'nullable|email|unique:companies,email,' . $this->get('id');
            $rules['alt_email'] = 'nullable|email|unique:companies,email,' . $this->get('id');
        }

        return $rules;
    }

}