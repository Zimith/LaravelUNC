<?php

namespace App\Http\Requests;

use App\Http\Requests\Concerns\ValidatesAdvanceState;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAvancesMultiplesRequest extends FormRequest
{
    use ValidatesAdvanceState;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $proyecto = $this->route('proyecto');

        return array_merge([
            'act' => ['required', 'array', 'min:1'],
            'act.*' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('actividades', 'id')->where('proyecto', $proyecto->id),
            ],
        ], $this->stateRules());
    }
}
