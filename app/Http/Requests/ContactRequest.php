<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class ContactRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'first_name'          => 'required|personName',
            'last_name'           => 'required|personName',
            'category_id'         => 'required|positive',
            'company_id'          => 'nullable|zeroOrPositive',
            'company_position_id' => 'nullable|zeroOrPositive',
            'overhead'            => 'nullable|zeroOrPositive|require_if:category_id,11',  // required if sub contractor (category_id = 11)
            'middle_name'         => 'nullable|personName',
            'salutation'          => 'nullable|personName|max:10',
            'title'               => 'nullable|plainText',
            'phone'               => 'nullable|phone',
            'alt_phone'           => 'nullable|phone',
            'alt_email'           => 'nullable|email',
            'address'             => 'nullable|plainText',
            'address_2'           => 'nullable|plainText',
            'city'                => 'nullable|plainText',
            'zipcode'             => 'nullable|plainText',
            'date_of_birth'       => 'nullable|usDate',
            'state_id'            => 'nullable|zeroOrPositive',
            'country_id'          => 'nullable|zeroOrPositive',
            'comment'             => 'nullable|plainText',
            'qualified'           => 'nullable|boolean',
            'disabled'            => 'boolean',
        ];

        if ($this->isMethod('post')) {                                                      // creating new contact
            $rules['email'] = 'nullable|email|unique:users';

        } else if ($this->isMethod('patch')) {                                              // updating contact
            $rules['email'] = 'nullable|email|unique:users,email,' . $this->get('id');
        }

        return $rules;
    }
}
