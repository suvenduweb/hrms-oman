<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if(isset($this->user_id)){
            return [
                'role_id'=>'required',
                'user_name'=>'required|regex:/^\S*$/u|unique:user,user_name,'.$this->user_id.',user_id',
            ];
        }

        return [
            'role_id'=>'required',
            'user_name' => 'required|unique:user|regex:/^\S*$/u',
            'password'=>'required|confirmed',
        ];
    }
}
