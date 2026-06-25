<?php
// app/Models/PaymentSetting.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentSetting extends Model
{
    protected $fillable = [
        'mode','test_key','test_secret','live_key','live_secret',
        'razorpay_enabled','cod_enabled','shipping_charge','free_shipping_above',
    ];

    protected $casts = [
        'razorpay_enabled' => 'boolean',
        'cod_enabled'      => 'boolean',
    ];

    public function getActiveKeyAttribute(): ?string
    {
        return $this->mode === 'live' ? $this->live_key : $this->test_key;
    }

    public function getActiveSecretAttribute(): ?string
    {
        return $this->mode === 'live' ? $this->live_secret : $this->test_secret;
    }
}