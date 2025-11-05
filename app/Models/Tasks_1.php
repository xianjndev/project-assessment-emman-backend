<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks_1 extends Model
{
    /** @use HasFactory<\Database\Factories\Tasks1Factory> */
    use HasFactory;
    protected $fillable = ['user_id', 'title','description', 'status','priority', 'isCompleted', 'order'];
}
