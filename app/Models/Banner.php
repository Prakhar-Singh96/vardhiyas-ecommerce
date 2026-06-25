<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'badge_text',
        'button_text', 'button_url',
        'button2_text', 'button2_url',
        'image', 'mobile_image',
        'position', 'text_color', 'text_align',
        'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive($q)   { return $q->where('is_active', true); }
    public function scopeHero($q)     { return $q->where('position', 'hero'); }
    public function scopePromo($q)    { return $q->where('position', 'promo'); }

    public function getImageUrlAttribute(): string
    {
        return asset('storage/' . $this->image);
    }

    public function getMobileImageUrlAttribute(): ?string
    {
        return $this->mobile_image
            ? asset('storage/' . $this->mobile_image)
            : null;
    }
}