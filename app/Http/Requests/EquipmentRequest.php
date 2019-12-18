<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class EquipmentRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'description' => 'nullable|plainText',
            'cost' => 'required|currency',
            'min_cost'        => 'required|currency',
        ];

        if ($this->isMethod('post')) {                                                        // creating new equipment
            $rules['name'] = 'required|plainText|unique:equipments';
        } else if ($this->isMethod('patch')) {                                                // updating equipment
            $rules['name'] = 'required|plainText|unique:equipments,name,' . $this->get('id');
        }

        return $rules;
    }

}