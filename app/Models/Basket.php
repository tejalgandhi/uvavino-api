<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Basket extends Model
{

    use HasFactory;
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'baskets';
    // protected $primaryKey = 'id';
    // public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];
    const IS_BESTSELLER = '1';
    const IS_NEW = '1';
    const NUMBER_OF_ITEMS_PER_PAGE = 12;
    const NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE = 4;
    const NUMBER_OF_NEW_ITEMS_PER_PAGE = 4;
    const RECENT_VIEW_LIMIT = 4;
    public function products(): HasMany
    {
        return $this->hasMany(Product::class,'basket_id','id');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($basket) {
            $slug = Str::slug($basket->title);
            $count = 1;
            while(static::whereSlug($slug)->count() > 0) {
                $slug = Str::slug($basket->title . "-" . $count);
                $count++;
            }
            $basket->slug = $slug;
        });
    }
    public static function getBestSellersProduct()
    {
        return self::where('bestsellers', Basket::IS_BESTSELLER)->orderBy('bestsellers_order', 'ASC')->paginate(self::NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE);
    }

}
