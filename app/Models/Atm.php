<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use Transaction model for relationship
use App\Models\Transaction;
//use BanknotesAtm model for relationship
use App\Models\BanknotesAtms;

class Atm extends Model
{
    use HasFactory;

    //fillable name and location
    protected $fillable = [
        'name',
        'address',
        'status'
    ];

    //atm relationship with transaction
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    //atm relationship with bankotes atms
    public function banknotesAtms(){
        return $this->hasMany(BanknotesAtms::class);
    }
    
}
