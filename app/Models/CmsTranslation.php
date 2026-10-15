<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

class CmsTranslation extends Model
{
    protected $table = 'cms_translations';

    const CREATED_AT = null;

    protected $fillable = [
        'language_id',
        'key',
        'value',
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    /**
     * Alle vaste teksten in de gevraagde taal, met de standaardtaal als terugval.
     *
     * @return Collection<string, string>
     */
    public static function texts(?string $code = null): Collection
    {
        $code ??= app()->getLocale();

        return static::query()
            ->join('languages', 'languages.id', '=', 'cms_translations.language_id')
            ->where(fn ($query) => $query->where('languages.code', $code)->orWhere('languages.is_default', true))
            ->orderByRaw('languages.code = ?', [$code])
            ->pluck('cms_translations.value', 'cms_translations.key');
    }
}
