<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    // Define the table if it's not the plural form of the model
    protected $table = 'activities';

    // Define the fillable attributes (if you want mass assignment)
    protected $fillable = [
        'user_id',
        'activity',
        'status'
    ];

    // Optional: Set default values for some columns if needed
    protected $attributes = [
        'status' => 'pending', // Default status
    ];

    // Relationship with the User model (assuming the 'user_id' column is a foreign key)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
