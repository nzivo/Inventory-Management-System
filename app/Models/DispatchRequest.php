<?php

namespace App\Models;

use App\Models\Assets\SerialNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approver_id',
        'item_id',
        'quantity',
        'dispatch_number',
        'description',
        'status',
        'site'
    ];

    // Relationship with User (Employee)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Item
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // DispatchRequest belongs to an approver (who approves the request)
    public function approver()
    {
        return $this->belongsTo(User::class, 'approver_id');
    }

    // Generate a unique dispatch number
    public static function generateDispatchNumber()
    {
        return strtoupper(bin2hex(random_bytes(6)));  // 12 characters hex (6 bytes)
    }

    // Relationship to SerialNumbers
    public function serialNumbers()
    {
        return $this->hasMany(DispatchRequestSerialNumber::class);
    }
}
