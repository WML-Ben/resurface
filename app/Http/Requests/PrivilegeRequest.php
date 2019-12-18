<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class PrivilegeRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        if ($this->isMethod('post')) {                                                        // creating new privilege
            $rules['name'] = 'required|slug|unique:privileges';
        } else if ($this->isMethod('patch')) {                                                // updating privilege
            $rules['name'] = 'required|slug|unique:privileges,name,' . $this->get('id');
        }

        return $rules;
    }

}