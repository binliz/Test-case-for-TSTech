<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deposit extends Model
{

    protected $casts = [
        'created_at' => 'datetime:Y-m-d',
    ];
    //
    public function transactions(){
        return $this->hasMany(Transaction::class);
    }
    public function client(){
        return $this->belongsTo(Client::class);
    }

}
