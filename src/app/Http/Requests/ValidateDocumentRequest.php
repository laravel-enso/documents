<?php

namespace LaravelEnso\Documents\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Documents\app\Exceptions\DocumentException;

class ValidateDocumentRequest extends FormRequest
{
    public function authorize()
    {
        $this->checkParams();

        return true;
    }

    public function rules()
    {
        return [
            'documentable_id' => 'required',
            'documentable_type' => 'required',
        ];
    }

    public function checkParams()
    {
        if (! class_exists($this->get('documentable_type'))) {
            throw new DocumentException(
                'The "documentable_type" property must be a valid model class'
            );
        }
    }
}
