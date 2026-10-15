<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsSection extends Model
{
    protected $table = 'cms_sections';

    public $timestamps = false;

    protected $fillable =
    [
        'page_id',
        'key',
        'sort_order',
        'is_visible'
    ];

    protected function casts(): array
    {
        return ['is_visible' => 'boolean'];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(CmsPage::class, 'page_id');
    }

    public function contents(): HasMany
    {
        return $this->hasMany(CmsContent::class, 'section_id')->orderBy('id');
    }
}
