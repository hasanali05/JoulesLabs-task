<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FormInputRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'is_public' => 'required|boolean',
            'is_published' => 'required|boolean',
            'fields' => 'required|array|min:1',
            'fields.*.name' => 'required|string|max:255',
            'fields.*.options' => 'nullable|string|max:255',
            'fields.*.type' => 'required|string|in:text,number,radio,checkbox,select-option,textarea',
            'fields.*.rules' => 'required|array|min:1',
            'fields.*.rules.*.logic' => 'required|in:required,min,max',
            'fields.*.rules.*.value' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'fields.*.name' => 'name',
            'fields.*.options' => 'options',
            'fields.*.type' => 'type',
            'fields.*.rules.*.logic' => 'Logic',
            'fields.*.rules.*.value' => 'Logic value',
        ];
    }
}