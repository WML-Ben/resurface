<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class CalendarEventRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'user_id'     => 'required|positive',
            'started_at'  => 'required|usDateTime',
            'ended_at'    => 'required|usDateTime',
            'name'        => 'required|plainText',
            'description' => 'nullable|plainText',
        ];

        return $rules;
    }
}
