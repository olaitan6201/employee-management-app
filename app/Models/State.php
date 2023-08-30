<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name'
    ];

    function country()
    {
        return $this->belongsTo(Country::class);
    }

    function cities()
    {
        return $this->hasMany(City::class);
    }

    function employees()
    {
        return $this->hasMany(Employee::class);
    }
}
