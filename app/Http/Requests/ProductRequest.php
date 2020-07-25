<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:30'],
            'short_desc' => ['nullable','string', 'max:30'],
            'desc' => ['string','nullable'],
            'specify' => ['string','nullable'],
            'terms' => ['string','nullable'],
            'start_price' => ['required', 'numeric', 'min:1'],
            'full_price' => ['required', 'numeric', 'min:1'],
            'bot_shutdown_price' => ['required', 'numeric', 'min:1'],
            'step_time' => ['required', 'integer', 'min:1'],
            'step_price' => ['required', 'integer', 'min:1'],
            'to_start' => ['required', 'integer', 'min:1'],
            'file_1' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048'],
            'file_2' => ['sometimes', 'required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048', 'nullable'],
            'file_3' => ['sometimes', 'required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048', 'nullable'],
            'file_4' => ['sometimes', 'required', 'image', 'mimes:jpeg,jpg,png,gif,svg', 'max:2048', 'nullable'],
            'alt_1' => ['nullable', 'string', 'max:50'],
            'alt_2' => ['nullable', 'string', 'max:50'],
            'alt_3' => ['nullable', 'string', 'max:50'],
            'alt_4' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function attributes()
    {
        return [
            'short_desc' => '',
            'title' => '',
            'desc' => '',
            'specify' => '',
            'terms' => '',
            'start_price' => '',
            'full_price' => '',
            'bot_shutdown_price' => '',
            'step_time' => '',
            'step_price' => '',
            'to_start' => '',
            'file_1' => '',
            'file_2' => '',
            'file_3' => '',
            'file_4' => '',
        ];
    }

}
