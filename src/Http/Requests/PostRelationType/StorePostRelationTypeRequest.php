<?php

declare(strict_types=1);

namespace Molitor\CmsPostRelations\Http\Requests\PostRelationType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class StorePostRelationTypeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::allows('acl', 'cms_post_relations');
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:post_relation_types,slug', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/'],
            'description' => ['nullable', 'string'],
        ];
    }
}
