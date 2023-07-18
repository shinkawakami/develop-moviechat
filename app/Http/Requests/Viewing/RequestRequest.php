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
            'movie' => 'required|integer',  
            'start_time' => 'required|date_format:Y-m-d\TH:i',
        ];
    }
}