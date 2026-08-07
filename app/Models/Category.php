<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name',
    'type',
    'parent_id',
    'is_active',
    'rooms',
    'business_channels',
])]
class Category extends Model
{
    public const TYPE_CATEGORY = 'category';

    public const TYPE_SUBCATEGORY = 'subcategory';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'rooms' => 'array',
            'business_channels' => 'array',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function isSubcategory(): bool
    {
        return $this->type === self::TYPE_SUBCATEGORY;
    }
}
