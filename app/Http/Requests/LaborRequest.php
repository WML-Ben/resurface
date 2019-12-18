<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class LaborRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'rate'        => 'required|currency',
            'd_sort'           => 'positive',
        ];

        if ($this->isMethod('post')) {                                                        // creating labor rate
            $rules['name'] = 'required|plainText|unique:labors';
        } else if ($this->isMethod('patch')) {                                                // updating labor rate
            $rules['name'] = 'required|plainText|unique:labors,name,' . $this->get('id');
        }

        return $rules;
    }

}