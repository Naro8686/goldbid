<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bet' => ['sometimes', 'integer', 'min:0'],
            'price' => ['sometimes', 'integer', 'min:0'],
            'bonus' => ['sometimes', 'integer', 'min:0'],
            'visibly' => ['sometimes', 'boolean'],
        ];
    }
}
