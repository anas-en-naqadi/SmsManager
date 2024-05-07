<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TwilioRequest extends FormRequest
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
        $userId = Auth::id();

        return [
            'service' => [
                'required',
                'string',
                Rule::unique('user_service_credentials','service_name')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
            'phone_number' => [
                'required',
                'string',
                Rule::unique('user_service_credentials')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
            'service_key' => [
                'required',
                'string',
                Rule::unique('user_service_credentials')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
            'service_token' => [
                'required',
                'string',
                Rule::unique('user_service_credentials')->where(function ($query) use ($userId) {
                    return $query->where('user_id', $userId);
                }),
            ],
        ];
    }

}
