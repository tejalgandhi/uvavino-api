<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class AuctionPackage extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    const IS_BESTSELLER = '1';
    const NUMBER_OF_ITEMS_PER_PAGE = 10;
    const NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE = 4;

    public function reviews()
    {
        return $this->morphMany(Review::class,'reviewable');
    }
//    public function items()
//    {
//        return $this->morphMany(OrderDetail::class,'orderable');
//    }
    /**
     * Image attribute getter
     *
     * @return Attribute
     */
    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Storage::url($value) : null,
        );
    }
}
