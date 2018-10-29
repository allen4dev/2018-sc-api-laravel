<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

use App\User;

class RegisterRequest extends FormRequest
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
            'username' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6'
        ];
    }

    public function registerUser()
    {
        return User::create([
            'username' => $this->username,
            'email' => $this->email,
            'password' => bcrypt($this->password),
        ]);
    }
}
