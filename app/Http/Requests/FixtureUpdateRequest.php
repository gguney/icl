<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FixtureUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'home_team_score' => 'required|numeric|min:0',
            'away_team_score' => 'required|numeric|min:0',
        ];
    }
}
