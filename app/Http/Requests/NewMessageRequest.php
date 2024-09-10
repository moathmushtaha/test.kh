<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewMessageRequest extends FormRequest
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
            'message' => 'required|string|max:255',
            'to_user_id' => 'required|integer|exists:users,id',
            'longitude' => 'nullable|numeric',
            'latitude' => 'nullable|numeric',
        ];
    }

    public function passedValidation()
    {
        $this->merge([
            'from_user_id' => auth()->id()
        ]);
    }
}
