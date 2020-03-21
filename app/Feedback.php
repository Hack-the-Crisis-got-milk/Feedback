<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    protected $fillable = [
        'type', 'item_group_id', 'value', 'shop_id'
    ];
}
