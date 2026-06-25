<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterValue extends Model
{
    protected $fillable = ['filter_type_id', 'value', 'label', 'meta', 'sort_order', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function filterType()
    {
        return $this->belongsTo(FilterType::class);
    }
}