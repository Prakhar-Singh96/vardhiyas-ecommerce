<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MoodSection extends Model
{
    protected $fillable = [
        'admin_label', 'image', 'label_top', 'label_main',
        'category_id', 'custom_url',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // URL auto-generate: category slug ya custom url
    public function getUrlAttribute(): string
    {
        if ($this->category) {
            return route('collection.show', $this->category->slug);
        }
        return $this->custom_url ?? '#';
    }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }
}