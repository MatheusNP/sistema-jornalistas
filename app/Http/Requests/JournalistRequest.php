<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

/**
 * Class JournalistRequest
 * @package App\Http\Requests
 */
class JournalistRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:journalists,email',
            'password' => 'required'
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
            'name' => 'trim|uppercase|escape',
            'first_name' => 'trim|escape',
            'last_name' => 'trim|escape',
            'email' => 'trim|lowercase|escape',
            'password' => function ($value) { return password_hash($value, PASSWORD_DEFAULT); }
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
            'first_name.required' => 'A first name is required',
            'first_name.string' => 'The first name must be a string',
            'last_name.required' => 'A last name is required',
            'last_name.string' => 'The last name must be a string',
            'email.required' => 'An e-mail is required',
            'email.email' => 'An e-mail is required',
            'password.required' => 'A password is required'
        ];
    }
}
