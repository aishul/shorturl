<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Links extends Model
{
    protected $fillable = [
        'url', 'code', 'clicks',
    ];
}
