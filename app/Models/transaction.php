<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class transaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'atm_id',
        'user_id',
        'type',
        'amount',
        'status',
        'date',
        'time',
        'banknotes'
    ];
    //transaction relationship with atm
    public function atm()
    {
        return $this->belongsTo(Atm::class);
    }
    //transaction relationship with user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
