<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class wallet extends Model
{
    use HasFactory;
    //fillable userid and balance and status
    protected $fillable = [
        'user_id',
        'balance',
        'status'
    ];
}
