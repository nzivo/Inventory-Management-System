<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'created_by', 'updated_by'];

    public function items()
    {
        return $this->hasMany(Item::class);
    }
    // Relationship to creator (the user who created the category)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by'); // 'created_by' is the foreign key
    }
}
