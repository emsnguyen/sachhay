<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateBookRequest extends FormRequest
{
    /**
     * @var mixed
     */
    private $images;

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
            'isbn' => 'unique:books|max:255',
            'publisher' => 'required|max:255',
            'review' => 'required|max:10000',
            'images'=> 'bail|required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ];
    }
}
