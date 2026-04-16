<?php

namespace App\Http\Requests\Salle;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

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
            'city' => ['required', 'string', 'max:255'],
            'tagline' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'sessionType' => ['nullable', 'string', 'max:255'],
            'existenceYears' => ['nullable', 'integer', 'min:0'],
            'sport_id' => ['required', 'integer', 'exists:sports,id'],
            'logo' => ['nullable', 'image', 'max:4096'],
            'background' => ['nullable', 'image', 'max:6144'],
            'galleries' => ['nullable', 'array', 'max:5'],
            'galleries.*' => ['image', 'max:6144'],
            'services' => ['nullable', 'array'],
            'services.*' => ['integer', 'exists:services,id'],
            'horaires' => ['nullable', 'array'],
            'horaires.*.open' => ['nullable', 'date_format:H:i'],
            'horaires.*.close' => ['nullable', 'date_format:H:i'],
            'equipment' => ['nullable', 'array'],
            'equipment.*.equipment_id' => ['required', 'integer', 'distinct', 'exists:equipments,id'],
            'equipment.*.condition' => ['required', 'in:excellent,good,fair,needs_repair'],
        ];
    }

    public function after(): array
    {
        return [
            function (Validator $validator): void {
                foreach ((array) $this->input('horaires', []) as $day => $hours) {
                    $openHour = $hours['open'] ?? null;
                    $closeHour = $hours['close'] ?? null;

                    if (!$openHour || !$closeHour) {
                        continue;
                    }

                    if ($openHour >= $closeHour) {
                        $validator->errors()->add(
                            "horaires.{$day}.open",
                            'The opening time must be earlier than the closing time.'
                        );
                    }
                }
            },
        ];
    }
}
