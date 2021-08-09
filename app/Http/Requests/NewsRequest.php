<?php

namespace App\Http\Requests;

use Anik\Form\FormRequest;
use Elegant\Sanitizer\Laravel\SanitizesInput;

/**
 * Class NewsRequest
 * @package App\Http\Requests
 */
class NewsRequest extends FormRequest
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
            'type_id' => 'required|integer',
            'title' => 'required|string',
            'description' => 'required|string',
            'body' => 'required|string',
            'img_link' => 'url'
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
            'title' => 'trim|escape',
            'description' => 'trim|escape',
            'body' => 'trim',
            'img_link' => 'trim|escape|empty_string_to_null'
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
            'journalist_id.required' => 'A journalist is required',
            'journalist_id.integer' => 'The journalist field must be an integer',
            'type_id.required' => 'A type is required',
            'type_id.integer' => 'The type field must be an integer',
            'title.required' => 'A title is required',
            'title.string' => 'The title field must be a string',
            'description.required' => 'A description is required',
            'description.string' => 'The description field must be a string',
            'body.required' => 'A body is required',
            'body.string' => 'The body field must be a string',
            'img_link.url' => 'The body field must be a URL'
        ];
    }
}
