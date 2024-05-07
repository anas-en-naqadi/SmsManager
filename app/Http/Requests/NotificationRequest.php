<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NotificationRequest extends FormRequest
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
            'to' => 'required|string',
            'from' => 'required|string',
            'body' => 'required|string',
            'service' => 'required|string',
            'scheduled_date'=>'nullable|string',
            'scheduled_time' => 'nullable|string',
            'status'=>'nullable',
        ];
    }
}
