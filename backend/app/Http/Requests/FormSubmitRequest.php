<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FormSubmitRequest extends FormRequest
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
        $form = $this->form;
        $form->load('fields', 'fields.rules');
        return [
            'fields' => 'required|array',
            'fields.*.id' => 'required|exists:fields,id',
            'fields.*.submit_value' => Rule::forEach(function ($value, $attribute) {
                $index = explode('.', $attribute)[1];
                return $this->getArrayOfRules($index);
            }),
        ];
    }

    public function attributes()
    {
        return [
            'fields.*.submit_value' => 'submitted value',
        ];
    }

    public function getArrayOfRules($index)
    {
        $field_id = $this->fields[$index]['id'];
        $rules = $this->form->fields->where('id', $field_id)->first()->rules;
        $array_of_rule = [];
        foreach ($rules as $rule) {
            $rule_string = '';
            if($rule->logic == 'required') {
                if($rule->value) {
                    $rule_string = 'required';
                } else {
                    $rule_string = 'sometimes';
                }
            } else {
                $rule_string = $rule->logic . ':' . $rule->value; 
            }

            if($rule_string != '') {
                $array_of_rule[] = $rule_string;
            }
        }
        return $array_of_rule;
    }
}
