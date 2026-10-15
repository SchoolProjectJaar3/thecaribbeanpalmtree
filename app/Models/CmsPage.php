<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CmsPage extends Model
{
    protected $table = 'cms_pages';

    protected $fillable = [
        'key',
        'template',
        'is_published',
        'show_in_menu',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'show_in_menu' => 'boolean',
        ];
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CmsPageTranslation::class, 'page_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(CmsSection::class, 'page_id')->orderBy('sort_order');
    }

    // Pages with all sections and translations 1x loaded
    public static function byKey(string $key): ?self
    {
        return static::with('sections.contents.language')->where('key', $key)->first();
    }

    // One text translation for a page with fallback on the default language, only works on a page with byKey() loaded
    public function text(string $section, string $key, ?string $code = null): ?string
    {
        $code ??= app()->getLocale();

        $sectionModel = $this->sections->firstWhere('key', $section);

        if (! $sectionModel) {
            return null;
        }

        $contents = $sectionModel->contents->where('key', $key);

        $match = $contents->first(fn($c) => $c->language->code === $code)
            ?? $contents->first(fn($c) => $c->language->is_default);

        return $match?->content;
    }
}
