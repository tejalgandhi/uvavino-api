<?php

namespace App\Models;

use App\Mail\UserContactMail;
use App\Models\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class Inquiry extends Model
{
    use FileUploadTrait;
    Protected $table = 'inquiry';
    protected $guarded = ['id'];


    public static function boot() {

        parent::boot();

        static::created(function ($item) {
            Mail::to(config('mail.admin_mail'))->queue(new ContactMail($item));
            Mail::to($item->email)->queue(new UserContactMail($item));

        });
    }
}
