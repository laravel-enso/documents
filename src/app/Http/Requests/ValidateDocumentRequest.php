<?php

namespace LaravelEnso\DocumentsManager\app\Http\Requests;

use Illuminate\Database\Eloquent\Model;
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
            if (!class_exists($this->documentable_type)
                || !new $this->documentable_type instanceof Model) {
                throw new DocumentException(
                    'The "documentable_type" property must be a valid model class'
                );
            }
        });
    }
}
