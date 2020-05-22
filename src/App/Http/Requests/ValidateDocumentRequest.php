<?php

namespace LaravelEnso\Documents\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Helpers\App\Traits\TransformMorphMap;

class ValidateDocumentRequest extends FormRequest
{
    use TransformMorphMap;

    public function morphType(): string
    {
        return 'documentable_type';
    }

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
