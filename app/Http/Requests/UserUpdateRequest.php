<?php

namespace App\Http\Requests;

use App\Enums\RoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'username' =>  ['required', 'min:8', 'max:100', Rule::unique('users', 'username')->ignore($this->user)],
            'password' => 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).*/|min:8|max:100|nullable',
            'name' => 'required|min:8|max:100',
            'email' => 'required|email|min:8|max:100|',
            'role' => ['required', Rule::enum(RoleEnum::class)],
        ];
    }
}
