<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class TaskRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'assigned_to'  => 'required|positive',
            'status_id'    => 'required|positive',
            'name'         => 'required|plainText',
            'description'  => 'nullable|plainText',
            'response'     => 'nullable|plainText',
            'due_at'       => 'required|usDateTime',
            'completed_at' => 'required|usDateTime',
        ];

        return $rules;
    }
}
