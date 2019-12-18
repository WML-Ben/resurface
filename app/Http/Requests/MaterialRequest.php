<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class MaterialRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'cost' => 'required|currency',
            'alt_cost'        => 'required|currency',
        ];

        if ($this->isMethod('post')) {                                                        // creating new material
            $rules['name'] = 'required|plainText|unique:materials';
        } else if ($this->isMethod('patch')) {                                                // updating material
            $rules['name'] = 'required|plainText|unique:materials,name,' . $this->get('id');
        }

        return $rules;
    }

}