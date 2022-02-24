<?php

namespace LaravelEnso\Documents\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use LaravelEnso\Helpers\Traits\TransformMorphMap;

class ValidateDocument extends FormRequest
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
