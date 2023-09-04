<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $fillable = [
        'reviewable_type',
        'reviewable_id',
        'wine_user_id',
        'rating',
        'review_content'
    ];

    /**
     * Return relationship of belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function wineUser(){
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
    }

//    public function reviewable()
//    {
//        return $this->morphTo();
//    }




}
