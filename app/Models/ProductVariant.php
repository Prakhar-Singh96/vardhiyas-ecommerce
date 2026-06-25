<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 'size_id', 'color_id', 
        'stock', 'extra_price', 'sku_variant'
    ];

    public function product()  { return $this->belongsTo(Product::class); }

    public function size()
    {
        return $this->belongsTo(FilterValue::class, 'size_id');
    }

    public function color()
    {
        return $this->belongsTo(FilterValue::class, 'color_id');
    }

    public function isInStock(): bool { return $this->stock > 0; }
}