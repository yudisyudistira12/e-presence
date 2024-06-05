<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeSite extends Model
{
    use HasFactory;

    protected $table = "configuration_office";
    protected $fillable = [
        'location_office',
        'radius'
    ];
}
