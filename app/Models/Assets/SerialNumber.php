<?php

namespace App\Models\Assets;

use App\Models\Item;
use App\Models\SerialNumberLog;
use App\Models\User;
use App\Models\DispatchRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialNumber extends Model
{
    use HasFactory;

    // Specify the table if it doesn't follow Laravel's pluralization convention
    protected $table = 'serial_numbers';

    // The attributes that are mass assignable
    protected $fillable = [
        'item_id',
        'serial_number',
        'status',
        'user_id',
        'created_by',
        'updated_by',
        'category_id',
    ];

    // The attributes that should be hidden for arrays
    protected $hidden = [
        'created_by',
        'updated_by',
    ];

    // Define relationships

    // A SerialNumber belongs to an Item
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    // A SerialNumber is created by a user
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // A SerialNumber can also have an updater (for when it's updated)
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Define the relationship between SerialNumber and User
    public function user()
    {
        return $this->belongsTo(User::class);  // Assuming you have a 'user_id' field in the serial_numbers table
    }

    // Define the relationship to SerialNumberLog
    public function serialNumberLogs()
    {
        return $this->hasMany(SerialNumberLog::class);
    }

    public function dispatchRequests()
    {
        return $this->belongsToMany(DispatchRequest::class, 'dispatch_request_serial', 'serial_number_id', 'dispatch_request_id')->withTimestamps();
    }
}
