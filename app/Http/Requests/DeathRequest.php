<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeathRequest extends FormRequest
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
            'deceased_name'     =>      'required|min:2',
            'residence'         =>      'required|min:2',
            'date_death'        =>      'nullable|date',
            'place_burial'      =>      'required|min:2',
            'date_burial'       =>      'nullable|date',
            'book_no'           =>      'required|integer',
            'page_no'           =>      'required|integer',
            'entry_no'          =>      'required|integer',
            'minister_id'       =>      'required|integer'
        ];
    }
}
