<?php

namespace App;

use Illuminate\Database\Eloquent\Model ;

class User extends Model
{

    protected $fillable = [
        'name', 'email', 'contact', 'address', 'country', 'city', 'state', 'zip',
    ];
}
