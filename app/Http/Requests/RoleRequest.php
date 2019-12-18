<?php namespace App\Http\Requests;

use App\Http\Requests\Request;

class RoleRequest extends Request
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [];

        if ($this->isMethod('post')) {                        // creating new role
            $rules['name'] = 'required|slug|unique:roles';
        } else if ($this->isMethod('patch')) {                // updating  role
            if ($this->input('name') == 'root' && auth()->user()->own_role == 'root') {
                // skip to allow root to update its own role, due to parent_id is null
            } else {
                $rules['name'] = 'required|slug|unique:roles,name,' . $this->get('id');
            }
        }

        return $rules;
    }

}