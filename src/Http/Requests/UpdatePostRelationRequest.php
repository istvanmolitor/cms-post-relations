<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePostRelationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('acl', 'cms_post_relations');
    }

    public function rules(): array
    {
        $relationId = $this->route('post_relation')?->id;

        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'related_post_id' => [
                'required',
                'integer',
                'exists:posts,id',
                'different:post_id',
                Rule::unique('post_relations', 'related_post_id')
                    ->where(fn ($query) => $query->where('post_id', $this->input('post_id')))
                    ->ignore($relationId),
            ],
            'sort' => ['nullable', 'numeric', 'min:0'],
            'post_relation_id' => ['nullable', 'integer', 'exists:post_relation_types,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'post_id.required' => 'A poszt kiválasztása kötelező.',
            'post_id.exists' => 'A kiválasztott poszt nem található.',
            'related_post_id.required' => 'A kapcsolt poszt kiválasztása kötelező.',
            'related_post_id.exists' => 'A kapcsolt poszt nem található.',
            'related_post_id.different' => 'A kapcsolt poszt nem lehet ugyanaz, mint az alap poszt.',
            'related_post_id.unique' => 'Ez a poszt már hozzá van rendelve a kiválasztott poszthoz.',
            'post_relation_id.exists' => 'A kiválasztott kapcsolat típus nem található.',
        ];
    }
}
