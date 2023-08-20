<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;

class CreateRequest extends FormRequest
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
            'movie' => 'required|integer',
            'rating' => 'required|integer|between:1,5',
        ];
    }
}
