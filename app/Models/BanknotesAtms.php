<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BanknotesAtms extends Model
{
    use HasFactory;
    //table name and fillable
    protected $table = 'banknotesatms';
    protected $fillable = [
        'atm_id',
        'banknote_id',
        'quantity'
    ];
    //banknotes atms relationship with atm
    public function atm(){
        return $this->belongsTo(Atm::class);
    }
    //banknotes atms relationship with banknote
    public function banknote(){
        return $this->belongsTo(Banknote::class);
    }
}
