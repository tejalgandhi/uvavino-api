<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;
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
    const IS_NOT_AUCTION_PRODUCT = '0';
    const IS_AUCTION_PRODUCT = '1';
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
        // create a event to happen on deleting
        static::deleting(function($model)  {
            $model->deleted_by = Auth::user()->id ?? NULL;
            $model->save();
        });

        // create a event to happen on saving
        static::saving(function($model)  {
            $model->created_by = Auth::user()->id ?? NULL;
        });
    }
    public static function getBestSellersProduct()
    {
        $query = self::withCount(['wishlist'=> function($q)   {
            $q->where('wine_user_id',request()->user_id);
        } ])->orderBy('id', 'desc');
        return $query->take(Config('constant.recommended_items_per_page'))->get();
    }
    public function wishlist() :HasMany
    {
        return $this->hasMany(Wishlist::class,'ref_id','id')->where('type',Wishlist::PRODUCT_TYPE);
    }
//    public function reviews()
//    {
//        return $this->hasMany(Review::class,'reviewable_id','id')->where('reviewable_type',ReviewType::Product);
//    }
}
