<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class ItemRequest extends FormRequest
{

    public function rules(): array
    {
        $rules = [
            'name' => 'required|string|min:3|max:250',
            'description' => 'required|string|min:10|max:2000',
            'price' => 'required|numeric|gt:0|lte:99999.99',
            'category_id' => 'required|exists:categories,id,is_active,1',
            'is_active' => 'required|boolean'
        ];
        return $this->getRules($rules);
    }

    private function getRules($rules): array
    {
        $patchData = array_intersect_key($rules, $this->validationData());
        $this->appendToRequest();
        if ($this->isMethod('PATCH') && $patchData) {
            return $patchData;
        }
        return $rules;
    }

    protected function appendToRequest(): void
    {
        $this->merge([
            'is_active' => (bool)$this->is_active
        ]);
    }
}
