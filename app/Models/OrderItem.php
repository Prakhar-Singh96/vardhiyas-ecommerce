<?php
// app/Models/OrderItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id','product_id','product_name','image_path',
        'size_id','color_id','size_label','color_label',
        'price','qty','subtotal',
    ];

    public function order() { return $this->belongsTo(Order::class); }
    public function product() { return $this->belongsTo(Product::class); }
}