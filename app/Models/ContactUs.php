<?php

namespace App\Models;

use App\Models\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMail;

class ContactUs extends Model
{
    use FileUploadTrait;
    Protected $table = 'contact_us';
    protected $guarded = ['id'];


    public static function boot() {

        parent::boot();

        static::created(function ($item) {
            Mail::to(config('mail.admin_mail'))->queue(new ContactMail($item));

        });
    }
}
