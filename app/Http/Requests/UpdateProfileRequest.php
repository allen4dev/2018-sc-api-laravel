<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
            'fullname' => 'string|min:4',
            'username' => 'string|min:2',
            'avatar'   => 'image',
            'profile'   => 'image',
        ];
    }

    public function updateProfile($user)
    {
        $fields = $this->only(['fullname', 'username']);

        $newFields = $this->addImageFields($fields);
        
        $user->update($newFields);

        return $user;
    }

    private function addImageFields($fields)
    {
        $newFields = $fields;

        // ToDo: Refactor
        if ($this->file('avatar')) {
            $avatarPath = $this->file('avatar')->store('avatars', 'public');

            $newFields['avatar_url'] = $avatarPath;
        }

        if ($this->file('profile')) {
            $profilePath = $this->file('profile')->store('profile', 'public');

            $newFields['profile_image'] = $profilePath;
        }

        return $newFields;
    }
}
