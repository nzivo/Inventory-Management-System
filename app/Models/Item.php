<?php

namespace App\Models;

use App\Models\Assets\SerialNumber;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_img',
        'thumbnail_img',
        'name',
        'description',
        'serial_number',
        'category_id',
        'branch_id',
        'created_by',
        'status'
    ];

    // Relationship with User (who added the item)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relationship with Branch
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function serialNumbers()
    {
        return $this->hasMany(SerialNumber::class);
    }
}
