<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    CONST PRODUCT_REF_TYPE = 'product';

    public function wine_user(){
        return $this->belongsTo(WineUser::class,'user_id','id');
    }

}
