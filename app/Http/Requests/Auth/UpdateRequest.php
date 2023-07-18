<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique(User::class)->ignore($this->user()->id)],
            'movies' => ['nullable', 'array'], 
            'genres' => ['nullable', 'array', 'exists:genres,id'],  
            'platforms' => ['nullable', 'array', 'exists:platforms,id'],  
            'eras' => ['nullable', 'array', 'exists:eras,id'], 
            'introduction' => ['nullable', 'string'],
        ];
    }
}
