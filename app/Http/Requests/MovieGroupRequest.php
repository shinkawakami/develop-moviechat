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
            'group_name' => 'required',
            'group_capacity' => 'required|integer',
            'movie_title' => 'required',
            'movie_genre' => 'required',
            'movie_subscription' => 'required',
            'movie_released_at' => 'required|integer',
            'group_created_id' => 'required',
        ];
    }
}
