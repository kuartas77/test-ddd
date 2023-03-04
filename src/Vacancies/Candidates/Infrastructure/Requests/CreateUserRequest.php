<?php

namespace Src\Vacancies\Candidates\Infrastructure\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'source' => ['required', 'string'],
            'owner' => ['required', 'exists:users,id'],
            'created_by' => ['required', 'exists:users,id']
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'created_by' => $this->user()->id
        ]);
    }
}
