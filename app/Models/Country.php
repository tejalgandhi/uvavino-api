<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Country extends Model
{
    use HasFactory;

    CONST IS_ACTIVE = '1';

    protected static function boot() {
        parent::boot();

        static::creating(function ($country) {
            $slug = Str::slug($country->name);
            $count = 1;
            while(static::whereSlug($slug)->count() > 0) {
                $slug = Str::slug($country->name . "-" . $count);
                $count++;
            }
            $country->slug = $slug;
        });
    }

}
