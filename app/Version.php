<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table = 'version';
    protected $fillable = [
        'version', 'detail', 'url',
    ];
}
