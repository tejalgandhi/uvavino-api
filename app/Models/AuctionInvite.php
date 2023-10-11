<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Enums\InviteStatus;

class AuctionInvite extends Model
{
    protected $guarded = ['id'];


    public $casts =[
        'status'=>InviteStatus::class,
    ];

}
