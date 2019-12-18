<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'first_name'      => 'required|personName',
            'last_name'       => 'required|personName',
            'role_id'         => 'required|positive',
            'avatar'          => 'fileName',
            'disabled'        => 'boolean',
        ];

        if ($this->isMethod('post')) {                                                      // creating new user
            $rules['email'] = 'required|email|unique:users';
            $rules['password'] = 'required|password|min:6';
            $rules['repeat_password'] = 'required|same:password';

        } else if ($this->isMethod('patch')) {                                              // updating user
            $rules['email'] = 'required|email|unique:users,email,' . $this->get('id');
            $rules['password'] = 'nullable|password|min:6';
            $rules['repeat_password'] = 'nullable|same:password';
        }

        /*


        'middle_name',

        'salutation',
        'gender',

        'category_id',
        'avatar',
        'signature',
        'title',
        'date_of_birth',
        'phone',
        'alt_phone',
        'alt_email',
        'address',
        'address_2',
        'city',
        'zipcode',
        'state_id',
        'country_id',
        'billing_address',
        'billing_address_2',
        'billing_city',
        'billing_zipcode',
        'billing_state_id',
        'overhead',
        'qualified',
        'is_employee',
        'hired_at',
        'comment',
        'disabled',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',

        */
        return $rules;
    }
}
