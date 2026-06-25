<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'price', 'sale_price', 'sku', 'brand', 'status', 'is_featured', 'is_bestseller',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_bestseller' => 'boolean',
        'price'       => 'decimal:2',
        'sale_price'  => 'decimal:2',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    // Discount percentage calculate karo
    public function getDiscountPercentAttribute(): int
    {
        if (!$this->sale_price || $this->sale_price >= $this->price) return 0;
        return (int) round((($this->price - $this->sale_price) / $this->price) * 100);
    }

    // Total stock across all variants
    public function getTotalStockAttribute(): int
    {
        return $this->variants->sum('stock');
    }

    // Unique sizes available
    public function getAvailableSizesAttribute()
    {
        return $this->variants()
            ->where('stock', '>', 0)
            ->with('size')
            ->get()
            ->pluck('size')
            ->filter()
            ->unique('id');
    }

    public static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            $product->slug = Str::slug($product->name);
        });
    }
}