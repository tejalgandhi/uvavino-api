<?php

namespace App\Models;

use App\Contracts\ConnectableUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class WineUser extends Authenticatable implements ConnectableUser
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'last_name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function updateConnectId($id): void {
        $this->connection_id = $id;
        $this->save();
        $this->refresh();
    }

    public function getConnectId(): string {
        return $this->connection_id;
    }

    public function getEmail(): string {
        return $this->email;
    }
  
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

}
