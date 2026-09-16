<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlogCategoryTranslation extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'blog_category_id',
        'locale',
        'name',
        'slug',
        'meta_title',
        'meta_description',
        'robots',
    ];

    public function blogCategory(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class);
    }
}
