<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarriageRequest extends FormRequest
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
            'husband_name'              =>  'required|min:2',
            'husband_father_name'       =>  'required|min:2',
            'husband_mother_name'       =>  'required|min:2',
            'husband_residence'         =>  'required|min:2',
            'husband_religion'          =>  'required|min:2',
            'husband_date_birth'        =>  'required|date',
            'wife_name'                 =>  'required|min:2',
            'wife_father_name'          =>  'required|min:2',
            'wife_mother_name'          =>  'required|min:2',
            'wife_residence'            =>  'required|min:2',
            'wife_religion'             =>  'required|min:2',
            'wife_date_birth'           =>  'required|date',
            'date_married'              =>  'required|date',
            'sponsors'                  =>  'required|min:2',
            'book_no'                   =>  'required|integer',
            'page_no'                   =>  'required|integer',
            'entry_no'                  =>  'required|integer',
            'remarks'                   =>  'nullable',
            'minister_id'               =>  'required|integer',
        ];
    }
}
