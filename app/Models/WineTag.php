<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class WineTag extends Model
{
    use HasFactory;
    protected $table = 'wine_tags';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    protected static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $slug = Str::slug($model->name);
            $count = 1;
            while(static::whereSlug($slug)->count() > 0) {
                $slug = Str::slug($model->name . "-" . $count);
                $count++;
            }
            $model->slug = $slug;
        });
    }
}
