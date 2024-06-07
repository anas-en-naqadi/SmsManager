<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DraftRequest extends FormRequest
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
            'to' => 'nullable',
            'from' => 'nullable',
            'body' => 'nullable',
            'service' => 'nullable',
            'scheduled_date' => 'nullable',
            'scheduled_time' => 'nullable',
            'category' => 'nullable'
        ];
    }
}
