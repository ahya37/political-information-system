<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InventoryModel extends Model
{
    protected $table   = 'inv_item';
    protected $guarded = [];
}
