<?php

namespace App\Http\Requests\Salle;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSalleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'sessionType' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'existenceYears' => ['nullable', 'integer', 'min:0'],
            'city' => ['required', 'string', 'max:255'],
            'coach_id' => ['required', 'integer', 'exists:coaches,id'],
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
        ];
    }
}
