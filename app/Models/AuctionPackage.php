<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuctionPackage extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    const IS_BESTSELLER = '1';
    const NUMBER_OF_ITEMS_PER_PAGE = 10;
    const NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE = 4;

    public static function getBestSellersPackage()
    {
        $query = self::where('bestsellers', AuctionPackage::IS_BESTSELLER);
        $paginate = request()->query('paginate') ?? 1;
        if ($paginate != 0)
        {
            return $query->paginate(self::NUMBER_OF_BESTSELLERS_ITEMS_PER_PAGE);
        }
        return $query;
    }
}
