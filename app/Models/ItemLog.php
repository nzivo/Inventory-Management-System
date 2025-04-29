<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemLog extends Model
{
    protected $fillable = ['item_id', 'action', 'quantity', 'logged_at', 'note'];
}
