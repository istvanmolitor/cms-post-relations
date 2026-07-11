<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class StorePostRelationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('acl', 'cms_post_relations');
    }

    public function rules(): array
    {
        return [
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'related_post_id' => ['required', 'integer', 'exists:posts,id', 'different:post_id'],
            'sort' => ['nullable', 'numeric', 'min:0'],
            'post_relation_type_id' => [
                'required',
                'integer',
                'exists:post_relation_types,id',
                Rule::unique('post_relations', 'post_relation_type_id')
                    ->where(fn ($query) => $query
                        ->where('post_id', $this->input('post_id'))
                        ->where('related_post_id', $this->input('related_post_id'))),
            ],
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
            'post_relation_type_id.required' => 'A kapcsolat típusának kiválasztása kötelező.',
            'post_relation_type_id.exists' => 'A kiválasztott kapcsolat típus nem található.',
            'post_relation_type_id.unique' => 'Ez a poszt már hozzá van rendelve a kiválasztott típussal ehhez a poszthoz.',
        ];
    }
}
