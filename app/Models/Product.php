<?php

namespace App\Models;

use App\Models\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;
    use FileUploadTrait;
    protected $table = 'products';

    protected $guarded = ['id'];

    public function wine_user(): BelongsTo
    {
        return $this->belongsTo(WineUser::class,'wine_user_id','id');
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

    public function wine_types(): BelongsTo
    {
        return $this->belongsTo(WineType::class,'wine_type','id');
    }

    public function wine_tag(): BelongsTo
    {
        return $this->belongsTo(WineTag::class);
    }
/*    public function reviewable(): MorphMany
    {
        return $this->morphMany(Review::class, 'reviewable');
    }*/

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
    public function rating_order_count()
    {
        return $this->reviews()
            ->selectRaw('count(rating) as rating_count, rating')
            ->groupBy('rating');

    }
    public function averageRating()
    {
        return number_format($this->reviews()->avg('rating'), 1);
    }
    public function countRating()
    {
        return $this->reviews()->count('rating');
    }
    public function productImages(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ? collect(json_decode($value))->map(fn($path) => Storage::url($path))->toArray() : null
        );
    }
}
