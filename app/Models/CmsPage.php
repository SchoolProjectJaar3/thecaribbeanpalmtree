<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

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

    public function contents(): HasManyThrough
    {
        return $this->hasManyThrough(CmsContent::class, CmsSection::class, 'page_id', 'section_id');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Haal een pagina op met alle secties, teksten en vertalingen in één keer.
     */
    public static function byKey(string $key): ?self
    {
        return static::with(['translations.language', 'sections.contents.language'])
            ->where('key', $key)
            ->first();
    }

    /**
     * De vertaling van de pagina in de gevraagde taal, met de standaardtaal als terugval.
     */
    public function translation(?string $code = null): ?CmsPageTranslation
    {
        $code ??= app()->getLocale();

        return $this->translations->first(fn (CmsPageTranslation $translation) => $translation->language->code === $code)
            ?? $this->translations->first(fn (CmsPageTranslation $translation) => $translation->language->is_default);
    }

    /**
     * Eén tekst van de pagina in de gevraagde taal, met de standaardtaal als terugval.
     *
     * @param  array<string, string|int|float>  $replace  Waarden voor placeholders, zoals ['aantal' => 5] voor :aantal.
     */
    public function text(string $section, string $key, array $replace = [], ?string $code = null): ?string
    {
        $code ??= app()->getLocale();

        $contents = $this->sections->firstWhere('key', $section)?->contents->where('key', $key);

        $content = $contents?->first(fn (CmsContent $content) => $content->language->code === $code)
            ?? $contents?->first(fn (CmsContent $content) => $content->language->is_default);

        if ($content?->content === null) {
            return null;
        }

        return strtr($content->content, collect($replace)->mapWithKeys(fn ($value, $name) => [':'.$name => $value])->all());
    }
}
