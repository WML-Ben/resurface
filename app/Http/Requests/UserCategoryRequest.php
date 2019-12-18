<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class UserCategoryRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'description'   => 'nullable|plainText',
        ];

        if ($this->isMethod('post')) {                                                        // creating new user category
            $rules['name'] = 'required|plainText|unique:user_categories';
        } else if ($this->isMethod('patch')) {                                                // updating user category
            $rules['name'] = 'required|plainText|unique:user_categories,name,' . $this->get('id');
        }

        return $rules;
    }

}