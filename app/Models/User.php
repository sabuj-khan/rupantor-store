<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Model
{
    use HasFactory;
    protected $fillable = [
        'email',
        'otp'
    ];

    public function customer_profile():HasOne{
        return $this->hasOne(CustomerProfile::class);
    }
}
