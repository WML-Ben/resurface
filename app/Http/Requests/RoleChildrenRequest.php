<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class RoleChildrenRequest extends Request
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role_id'  => 'required|positive',
            'child_id' => 'required|positive',
        ];
    }

}