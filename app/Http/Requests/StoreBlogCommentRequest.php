<?php

namespace App\Http\Requests;

use App\Models\BlogPost;
use App\Rules\ValidBlogCommentParent;
use Illuminate\Foundation\Http\FormRequest;

class StoreBlogCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $blogPostId = $this->resolvedBlogPostId();

        $parentRules = ['nullable', 'integer'];

        if ($blogPostId) {
            $parentRules[] = new ValidBlogCommentParent($blogPostId);
        }

        return [
            'author_name' => ['required', 'string', 'max:255'],
            'author_email' => ['nullable', 'email', 'max:255'],
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => $parentRules,
        ];
    }

    private function resolvedBlogPostId(): ?int
    {
        $slug = (string) $this->route('slug');
        $locale = (string) ($this->route('locale') ?? $this->query('locale') ?? app()->getLocale());

        if (blank($slug)) {
            return null;
        }

        $id = BlogPost::query()
            ->whereTranslation('slug', $slug, $locale)
            ->value('id');

        if (! $id) {
            $id = BlogPost::query()
                ->whereTranslation('slug', $slug)
                ->value('id');
        }

        return $id;
    }
}
