<?php

namespace App\Http\Requests\Concerns;

use App\Services\EstadosAvance\EstadoAvanceStrategyFactory;
use Illuminate\Validation\Rule;

trait ValidatesAdvanceState
{
    protected function stateRules(): array
    {
        $factory = app(EstadoAvanceStrategyFactory::class);
        $states = $factory->states();
        $rules = [
            'est' => ['required', Rule::in($states)],
            'obs' => ['nullable', 'string'],
        ];

        $state = $this->input('est');
        $validState = is_string($state) && in_array($state, $states, true);
        if ($validState) {
            $rules = array_merge($rules, $factory->for($state)->rules());
        }

        $fileRules = ['file', 'mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar', 'extensions:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,zip,rar'];
        $requiredFile = $validState && $factory->for($state)->requiresFile();
        $rules['fuenteV'] = array_merge($requiredFile ? ['required'] : ['nullable'], $fileRules);

        return $rules;
    }
}
