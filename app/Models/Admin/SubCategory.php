<?php

namespace App\Models\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;

    // Define the table name if it's not the plural of the model name
    protected $table = 'subcategories';

    // Define the fillable attributes
    protected $fillable = [
        'name',
        'category_id',
        'created_by',
        'updated_by',
    ];

    // Define the relationship with the Category model
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Define the relationship with the User model (created_by)
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Define the relationship with the User model (updated_by)
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
