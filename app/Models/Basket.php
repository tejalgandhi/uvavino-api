<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
    public function countryId() : Attribute
    {
        $firstProduct = $this->products()->first();
        return Attribute::make(
            fn () => $firstProduct?->country_id,
        );
    }
    public function countryName() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()->first()?->country?->name,
        );
    }
    public function countryIsoCode() : Attribute
    {
        return Attribute::make(
            fn () => $this->products()->first()?->country?->iso_alpha_2,
        );
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
        return $this->hasMany(Wishlist::class,'wishlistable_id','id')->where('wishlistable_type',Basket::class);
    }
    public function wine_user(): BelongsTo
    {
        return $this->belongsTo(WineUser::class,'created_by','id');
    }
//    public function reviews()
//    {
//        return $this->hasMany(Review::class,'reviewable_id','id')->where('reviewable_type',ReviewType::Product);
//    }
}
