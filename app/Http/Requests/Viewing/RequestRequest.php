<?php

namespace App\Http\Requests\Viewing;

use Illuminate\Foundation\Http\FormRequest;

class RequestRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    public function rules()
    {
        return [
            'recipients' => 'required|array|exists:users,id',
            'movie' => 'required|integer',  
            'start_time' => 'required|date_format:Y-m-d\TH:i',
        ];
    }
}