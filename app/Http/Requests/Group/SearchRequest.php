<?php

namespace App\Http\Requests\Group;

use Illuminate\Foundation\Http\FormRequest;

class SearchRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'keyword' => 'nullable|string|max:50',
            'eras' => 'nullable|array|exists:eras,id',
            'genres' => 'nullable|array|exists:genres,id',
            'platforms' => 'nullable|array|exists:platforms,id',
        ];
    }
}
