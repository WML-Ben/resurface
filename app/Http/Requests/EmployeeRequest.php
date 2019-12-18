<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class EmployeeRequest extends Request
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
            'role_id'             => 'required|positive',
            'category_id'         => 'nullable|zeroOrPositive',
            'middle_name'         => 'nullable|personName',
            'salutation'          => 'nullable|personName|max:10',
            'avatar'              => 'nullable|fileName',
            'signature'           => 'nullable|fileName',
            'title'               => 'nullable|plainText',
            'phone'               => 'required|phone',
            'alt_phone'           => 'nullable|phone',
            'alt_email'           => 'nullable|email',
            'address'             => 'nullable|plainText',
            'address_2'           => 'nullable|plainText',
            'city'                => 'nullable|plainText',
            'zipcode'             => 'nullable|plainText',
            'date_of_birth'       => 'nullable|usDate',
            'hired_at'            => 'nullable|usDate',
            'state_id'            => 'nullable|zeroOrPositive',
            'country_id'          => 'nullable|zeroOrPositive',
            'comment'             => 'nullable|plainText',
            'disabled'            => 'nullable|boolean',
        ];

        if ($this->isMethod('post')) {                                                      // creating new employee
            $rules['email'] = 'required|email|unique:users';
            $rules['password'] = 'required|password|min:6';
            $rules['repeat_password'] = 'required|same:password';

        } else if ($this->isMethod('patch')) {                                              // updating employee
            $rules['email'] = 'required|email|unique:users,email,' . $this->get('id');
            $rules['password'] = 'nullable|password|min:6';
            $rules['repeat_password'] = 'nullable|same:password';
        }

        return $rules;
    }
}
