<?php

namespace LaravelEnso\DocumentsManager\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
