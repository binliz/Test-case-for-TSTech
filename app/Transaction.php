<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = ['startsum','newsum','transactionname','transactionvalue','transactionvaluetext'];
    //
    public function deposit()
    {
        return $this->belongsTo(Deposit::class);
    }

    protected static function boot()
    {
        parent::boot();
        static::created(function ($model) {
            $deposit = $model->deposit();
            $deposit->update(['currentcost' => $model->newsum]);
        });
    }
}
