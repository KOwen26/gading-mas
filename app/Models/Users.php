<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Users extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users';
    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';

    // public function getAuthPassword()
    // {
    //     return 'password';
    // }

    // protected $hidden = [
    //     'user_password',
    // ];

    public function Transactions()
    {
        return $this->hasMany(Transactions::class, 'transaction_id', 'transaction_id');
    }

    public function Procurements()
    {
        return $this->hasMany(Procurements::class, 'procurement_id', 'procurement_id');
    }

    public function Actions()
    {
        return $this->hasMany(Actions::class, 'action_id', 'action_id');
    }

    public function userName(): Attribute
    {
        return new Attribute(
            get: fn ($value) => Str::title($value),
            set: fn ($value) => Str::lower($value),
        );
    }
}