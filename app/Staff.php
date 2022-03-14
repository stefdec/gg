<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function staffFixedSalary()
    {
        return $this->hasMany(StaffFixedSalaries::class);
    }
}
