<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterType extends Model
{
    protected $fillable = ['name', 'type', 'display_name', 'is_active', 'sort_order'];

    protected $casts = ['is_active' => 'boolean'];

    public function values()
    {
        return $this->hasMany(FilterValue::class)->orderBy('sort_order');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_filter_type');
    }

    // Type check helpers
    public function isSize(): bool  { return $this->name === 'Size'; }
    public function isColor(): bool { return $this->name === 'Color'; }
}