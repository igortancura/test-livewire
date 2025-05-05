<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Tree extends Model
{
    protected $table = 'tree';
    protected $fillable = [
        'parent_id',
        'name',
        'is_parent'
    ];
}
