<?php

namespace App\Http\Requests\Post;

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
            'title' => 'required|string|max:50',
            'content' => 'required|string|max:255',
            'movie' => 'nullable|integer'
        ];
    }
}
