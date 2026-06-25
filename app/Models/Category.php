<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'parent_id', 'image', 
        'description', 'sort_order', 'is_active', 'show_in_collection', 'is_look_category',  // ← add
    ];

    protected $casts = ['is_active' => 'boolean','show_in_collection'  => 'boolean', 'is_look_category'   => 'boolean'];

    // Self-referencing: parent category
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Sub-categories
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Is category mein kitne products hain
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Is category pe kaunse filter types applicable hain
    public function filterTypes()
    {
        return $this->belongsToMany(FilterType::class, 'category_filter_type');
    }

    // Auto slug generate
    public static function boot()
    {
        parent::boot();
        static::creating(function ($category) {
            $category->slug = Str::slug($category->name);
        });
    }

    // Scope: sirf parent categories
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    // Scope: sirf active
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}