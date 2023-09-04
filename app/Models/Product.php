<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';

    protected $guarded = ['id'];


    public function wine_user(): BelongsTo
    {
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
    }

    public function drink_type(): BelongsTo
    {
        return $this->belongsTo(DrinkType::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function producer(): BelongsTo
    {
        return $this->belongsTo(Producer::class);
    }

//    public function orders(): MorphMany
//    {
//        return $this->morphMany(OrderDetail::class, 'orderable');
//    }

    public function wine_type(): BelongsTo
    {
        return $this->belongsTo(WineType::class,'type_of_wine','id');
    }

    public function wine_tag(): BelongsTo
    {
        return $this->belongsTo(WineTag::class);
    }

    public function packaging(): HasOne
    {
        return $this->hasOne(Basket::class);
    }

    public function reviewable(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }

    protected static function boot() {
        parent::boot();

        static::creating(function ($product) {
            $slug = Str::slug($product->wine_name);
            $count = 1;
            while(static::whereSlug($slug)->count() > 0) {
                $slug = Str::slug($product->wine_name . "-" . $count);
                $count++;
            }
            $product->slug = $slug;
        });
    }

    public function recommended($count = NULL) {
        if($count == NULL) {
            $count = config('relavent_product.product_count');
        }
        $product = $this;
        $attributes = config('relavent_product.attributes');
        $rank = Arr::join(array_values(Arr::map($attributes, function($conf, $attribute) use ($product) {
            $expected = $product->$attribute;
            $score = Arr::get($conf, 'score', 1);
            $template_name = Arr::get($conf, 'template', 'default');
            $template = Arr::get(config('relavent_product.templates'), $template_name);
            return __($template, compact('attribute', 'expected', 'score'));
        })), " + ");

        $select = Arr::join(config('relavent_product.select', ["*"]),",") . ", ($rank) as `rank`";

        return static::selectRaw($select)
            ->orderBy('rank', 'desc')
            ->limit($count)
            ->get();
    }

    public function reviews()
    {
        return $this->morphMany(Review::class,'reviewable');
    }

    public function items()
    {
        return $this->morphMany(OrderDetail::class,'orderable');
    }
}
