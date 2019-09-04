<?php

namespace LaravelEnso\Documents\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Documents\app\Exceptions\DocumentException;

class ValidateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'documentable_id' => 'required',
            'documentable_type' => 'required',
        ];
    }
}
