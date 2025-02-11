<?php

namespace App\Models;

use App\Models\Assets\SerialNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNumberLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'serial_number_id',
        'user_id',
        'description',
    ];

    // Define the relationship between SerialNumberLog and SerialNumber
    public function serialNumber()
    {
        return $this->belongsTo(SerialNumber::class);
    }

    // Define the relationship between SerialNumberLog and User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
