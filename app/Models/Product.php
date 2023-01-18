<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $guarded = ['id'];


    public function wine_user(){
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
    }

    public function drink_type(){
        return $this->belongsTo(DrinkType::class);
    }

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function region(){
        return $this->belongsTo(Region::class);
    }

    public function producer(){
        return $this->belongsTo(Producer::class);
    }

    public function wine_type(){
        return $this->belongsTo(WineType::class,'type_of_wine','id');
    }

    public function wine_tag(){
        return $this->belongsTo(WineTag::class);
    }

}
