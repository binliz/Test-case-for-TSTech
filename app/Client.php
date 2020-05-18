<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    protected $fillable = ['idcode', 'name', 'lastname', 'sex', 'birthday'];

    //
    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

}
