<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class AutomaticBid extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function evalWith(AutomaticBid $automaticBid,Auction $auction)
    {
        Log::info('evalWith by user '.$this->wine_user_id.' Max limit '.$this->max_limit);
        if($this->auction_id != $auction->id || $automaticBid->auction_id != $auction->id)
        {
                throw new \Exception('auction  not matched');
        }
        // Check if the auction can be auto-bid with the current AutoBid
        if ($auction->makeAutobid($this)) {
            Log::info('evalWith by user '.$this->wine_user_id.' Autobid done');
            // If successful, recursively call evalWith on the opponent
//            if($this->wine_user_id !=  $automaticBid->wine_user_id) {
                return $automaticBid->evalWith($this, $auction);
//            }
        }
        Log::info('evalWith by user '.$this->wine_user_id.' limit exceed, returning '. $automaticBid->wine_user_id);
        // If the auto-bid fails, return the opponent
        return $automaticBid;
    }

    public function auction()
    {
        return $this->belongsTo(Auction::class,'auction_id','id');
    }

}
