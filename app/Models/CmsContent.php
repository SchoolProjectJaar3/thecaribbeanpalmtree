<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CmsContent extends Model
{
    protected $table = 'cms_contents';

    // the table does not have a created_at only updated_at column
    const CREATED_AT = null;

    protected $fillable = [
        'section_id',
        'language_id',
        'key',
        'type',
        'content',
        'media_id',
        'updated_by',
    ];

    public function section(): BelongsTo
    {
        return $this->belongsTo(CmsSection::class, 'section_id');
    }

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
