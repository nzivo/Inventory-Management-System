<?php

namespace App\Models;

use App\Models\Assets\SerialNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DispatchRequestSerialNumber extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispatch_request_id',
        'serial_number_id',
    ];

    // Relationship with DispatchRequest
    public function dispatchRequest()
    {
        return $this->belongsTo(DispatchRequest::class);
    }

    // Correct relationship with SerialNumber
    public function serialNumber()
    {
        return $this->belongsTo(SerialNumber::class, 'serial_number_id');
    }
}
