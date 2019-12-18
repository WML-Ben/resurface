<?php namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeoRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'page_title'       => 'required|plainText|min:5|max:70',
            'page_description' => 'required|plainText|min:5|max:160',
            'page_keywords'    => 'plainText|min:5|max:255',
        ];

        if ($this->isMethod('post')) {                                                      // creating new seo
            $rules['route_name'] = 'required|routeName|unique:seo';
        } else if ($this->isMethod('patch')) {                                              // updating seo
            $rules['route_name'] = 'required|routeName|unique:seo,route_name,' . $this->get('id');
        }

        return $rules;
    }

}
