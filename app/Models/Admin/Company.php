<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'postal_address',
        'postal_code',
        'primary_phone',
        'secondary_phone',
        'email',
        'url',
    ];
}
