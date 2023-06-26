<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MovieGroupRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'group.name' => 'required|string',
            'group.capacity' => 'required|integer',
            'movie.title' => 'required|string',
            'genre.name' => 'required|string',
            'subscription.name' => 'required|string',
            'movie.released_at' => 'required|integer',
            'group.created_id' => 'required|integer',
        ];
    }
}
