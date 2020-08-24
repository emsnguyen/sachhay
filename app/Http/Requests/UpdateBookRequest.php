<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
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
            'title' => 'bail|required|max:255',
            'author' => 'required|max:255',
            'isbn' => 'required|max:255|unique:books,id,:id',
            'publisher' => 'required|max:255',
            'review' => 'required|max:10000',
            'images'=> 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
