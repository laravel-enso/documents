<?php

namespace LaravelEnso\DocumentsManager\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\DocumentsManager\app\Exceptions\DocumentException;

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

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if (! class_exists($this->get('documentable_type'))) {
                throw new DocumentException(
                    'The "documentable_type" property must be a valid model class'
                );
            }
        });
    }
}
