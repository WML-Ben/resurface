<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CompanyCategoryRequest extends Request
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

        if ($this->isMethod('post')) {                                                        // creating new company category
            $rules['name'] = 'required|plainText|unique:company_categories';
        } else if ($this->isMethod('patch')) {                                                // updating company category
            $rules['name'] = 'required|plainText|unique:company_categories,name,' . $this->get('id');
        }

        return $rules;
    }

}