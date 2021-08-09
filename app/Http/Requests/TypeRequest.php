<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

/**
 * Class TypeRequest
 * @package App\Http\Requests
 */
class TypeRequest extends FormRequest
{
    use SanitizesInput;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    protected function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [
            'name' => 'required|string'
        ];
    }

    /**
     * Makes the sanitization of the request.
     *
     * @return array
     */
    protected function filters(): array
    {
        return [
            'name' => 'trim|uppercase|escape'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    protected function messages(): array
    {
        return [
            'name.required' => 'A name is required',
            'name.string' => 'The name field must be a string'
        ];
    }
}
