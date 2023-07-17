<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class EditRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'group_name' => 'required|string|max:50',
            'group_capacity' => 'required|integer|min:2|max:10',
            'genres' => 'nullable|array|exists:genres,id', 
            'eras' => 'nullable|array|exists:eras,id', 
            'platforms' => 'nullable|array|exists:platforms,id',
            'movies' => 'nullable',
        ];
    }
}
