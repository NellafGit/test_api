<?php

namespace App\Http\Requests\Api\publ\Author;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
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
            'name' => 'string|max:255',
            'surname' => 'string|max:255',
            'year' => 'integer|max:9999',
            'books' => 'filled',
            'books.*' => 'array',
            'books.*.title' => 'string|filled|max:255',
            'books.*.content' => 'string|filled|max:255',
            'books.*.publish_year' => 'integer|filled|max:9999',
            'photo' => 'filled|mimes:jpeg,jpg,png,gif|max:10000',
        ];
    }
}
