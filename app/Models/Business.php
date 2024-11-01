<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $table = 'business';

    protected $fillable = [
        'status',
        'opening_hours',
        'name'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
