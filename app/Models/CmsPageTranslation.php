<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsPageTranslation extends Model
{
    protected $table = 'cms_page_translations';

    public $timestamps = false;

    protected $fillable =
    [
        'page_id',
        'language_id',
        'title',
        'slug',
        'meta_title',
        'meta_description',
    ];

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }
}
