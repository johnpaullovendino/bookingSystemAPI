<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $table = 'bookings';

    protected $fillable = [
        'service_id',
        'duration',
        'name',
        'phoneNumber',
        'email',
        'amount',
        'payment_method',
        'note',
        'promo',
        'promo_code',
        'discount',
        'total_amount'
    ];

    // public function user() 
    // {
    //     return $this->belongsTo(User::class);
    // }

    public function service() 
    {
        return $this->belongsTo(Service::class);
    }
}
