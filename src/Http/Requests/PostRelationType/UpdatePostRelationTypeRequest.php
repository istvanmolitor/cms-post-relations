<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Requests\PostRelationType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePostRelationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('acl', 'cms_post_relations');
    }

    public function rules(): array
    {
        $typeId = $this->route('post_relation_type')?->id;

        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('post_relation_types', 'slug')->ignore($typeId),
            ],
            'description' => ['nullable', 'string'],
        ];
    }
}
