<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Notifications\Notifiable;
use Laravel\Lumen\Auth\Authorizable;

class User extends Model implements \Illuminate\Contracts\Auth\Authenticatable, CanResetPassword
{
    use Authenticatable, Authorizable, HasFactory;
    use \Illuminate\Auth\Passwords\CanResetPassword;
    use Notifiable;

    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'phone', 'api_token'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    protected $casts = [
        'password' => 'hashed'
    ];

    public function companies(): HasMany
    {
        return $this->hasMany(Company::class);
    }
}
