<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'address',
        'department_id',
        'city_id',
        'state_id',
        'country_id',
        'zip_code',
        'birth_date',
        'date_hired',
    ];

    function state()
    {
        return $this->belongsTo(State::class);
    }

    function city()
    {
        return $this->belongsTo(City::class);
    }

    function country()
    {
        return $this->belongsTo(Country::class);
    }

    function department()
    {
        return $this->belongsTo(Department::class);
    }
}
