<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';
    protected $fillable = [
        'book_id',
        'duration',
        'name',
        'service_name',
        'amount',
        'payment_method',
        'payment_status',
        'status',
        'note',
        'promo',
        'promo_code',
        'discount',
        'total_amount'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function service() 
    {
        return $this->belongsTo(Service::class);
    }
}
