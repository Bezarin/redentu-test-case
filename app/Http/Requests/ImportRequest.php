<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'mimes:xlsx',
                'mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ],
        ];
    }
}
