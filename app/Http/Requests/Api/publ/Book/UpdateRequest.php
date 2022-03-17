<?php

namespace App\Http\Requests\Api\publ\Book;

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
            'title' => 'string|max:255',
            'content' => 'string|max:255',
            'published_year' => 'integer|max:9999',
            'authors.*' => 'array',
            'authors.*.name' => 'string|required|max:255',
            'authors.*.surname' => 'string|required|max:255',
            'authors.*.year' => 'integer|required|max:9999',
        ];
    }
}
