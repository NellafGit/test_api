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
            'authors' => 'filled',
            'authors.*' => 'array',
            'authors.*.name' => 'string|filled|max:255',
            'authors.*.surname' => 'string|filled|max:255',
            'authors.*.year' => 'integer|filled|max:9999',
            'photo' => 'filled|mimes:jpeg,jpg,png,gif|max:10000',
        ];
    }
}
