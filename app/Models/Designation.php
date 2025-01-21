<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Designation extends Model
{
    use HasFactory;

    // Specify the table if it doesn't follow Laravel's naming convention
    // protected $table = 'designations';

    // Define the fillable attributes to allow mass assignment
    protected $fillable = [
        'designation_name',
        'department_name',
    ];

    /**
     * Get the users that belong to the designation.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
