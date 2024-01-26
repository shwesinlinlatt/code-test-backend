<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Volunteer extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    use HasFactory;

    public function patients()
    {
        return $this->hasMany(Patient::class, 'volunteer_id');
    }
}
