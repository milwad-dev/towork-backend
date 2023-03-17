<?php

namespace Modules\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() === true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' =>  [ 'required' , 'string' , 'min:3' ,  'max:250' , Rule::unique('categories' , 'title') ],
            'user_id' =>  [ 'required' , 'numeric' , Rule::exists('users' ,'id') ],

        ];
    }
}
