<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Deligoez\LaravelModelHashId\Traits\HasHashId;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Energy extends Model
{
    use HasFactory, HasHashId;

    protected $fillable = [
        'code',
        'label',
    ];
}
