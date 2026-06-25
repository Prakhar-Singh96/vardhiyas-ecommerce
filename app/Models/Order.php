<?php
// app/Models/Order.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'order_number','customer_name','customer_email','customer_phone',
        'address_line','locality','city','state','pincode',
        'subtotal','shipping_charge','total',
        'payment_method','payment_status',
        'razorpay_order_id','razorpay_payment_id','razorpay_signature',
        'status','whatsapp_sent_at',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending'    => 'warning',
            'confirmed'  => 'info',
            'processing' => 'primary',
            'shipped'    => 'secondary',
            'delivered'  => 'success',
            'cancelled'  => 'danger',
            default      => 'secondary',
        };
    }
}