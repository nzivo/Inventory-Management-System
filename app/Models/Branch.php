<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    protected $fillable = ['name', 'location', 'created_by', 'updated_by'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    // Relationship to creator (the user who created the branch)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); // 'created_by' is the foreign key
    }
}
