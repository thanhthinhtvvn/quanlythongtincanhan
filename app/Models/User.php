<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    protected $table = 'users';

    const UPDATED_AT = null;

    protected $fillable = [
        'email',
        'password',
        'role',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    public function personalInformation(): HasOne
    {
        return $this->hasOne(PersonalInformation::class, 'user_id', 'id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(Education::class, 'user_id', 'id');
    }

    public function skills(): HasMany
    {
        return $this->hasMany(Skill::class, 'user_id', 'id');
    }

    public function hobbies(): HasMany
    {
        return $this->hasMany(Hobby::class, 'user_id', 'id');
    }

    public function transcripts(): HasMany
    {
        return $this->hasMany(Transcript::class, 'user_id', 'id');
    }
}