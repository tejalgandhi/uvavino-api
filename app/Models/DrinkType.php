<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DrinkType extends Model
{
    use HasFactory;

    protected $table = 'drink_types';
    protected $guarded = ['id'];
}
