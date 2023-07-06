<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    use HasFactory;
    protected $table = 'wishlist';

    protected $guarded = ['id'];

    public function wine_user(){
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
    }

    public function basket(){
        return $this->belongsTo(Basket::class,'basket_id','id');
    }
}
