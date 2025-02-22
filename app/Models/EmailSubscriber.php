<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailSubscriber extends Model
{
    use HasFactory;

    // Define the table associated with the model
    protected $table = 'email_subscribers';

    // Define the fillable fields
    protected $fillable = [
        'email',
        'type',
    ];

    // Optionally, you can define the types of notifications this subscriber can receive
    const NOTIFICATION_TYPES = [
        'stock_notification',
        'license_renewal',
        'dispatch_request',
    ];

    /**
     * Check if the subscriber is interested in a specific notification type.
     */
    public function isSubscribedTo($type)
    {
        return in_array($type, self::NOTIFICATION_TYPES) && $this->type === $type;
    }
}
