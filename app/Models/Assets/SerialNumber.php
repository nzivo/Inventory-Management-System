<?php

namespace App\Models\Assets;

use App\Models\Item;
use App\Models\User;
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
        'created_by',
        'updated_by',
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
        return $this->belongsTo(Item::class);
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
}
