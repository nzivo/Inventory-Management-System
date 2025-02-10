<?php

namespace App\Models;

use App\Models\Admin\Brand;
use App\Models\Admin\SubCategory;
use App\Models\Admin\Supplier;
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
        'model_number',
        'category_id',
        'subcategory_id', // Added
        'brand_id', // Added
        'supplier_id', // Added
        'branch_id',
        'created_by',
        'status',
        'quantity', // Added
        'threshold',
        'available_quantity',
        'inventory_status'
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

    public function subcategory() // Added relationship
    {
        return $this->belongsTo(SubCategory::class);
    }

    public function brand() // Added relationship
    {
        return $this->belongsTo(Brand::class);
    }

    public function supplier() // Added relationship
    {
        return $this->belongsTo(Supplier::class);
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

    // Check if enough stock is available for a request
    public function hasEnoughStock($quantity)
    {
        return $this->quantity >= $quantity;
    }
}
