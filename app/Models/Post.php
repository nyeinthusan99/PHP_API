<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'title' ,
        'description',
        'status',
        'user_id',
        'create_user_id',
        'updated_user_id',
        'deleted_user_id'
    ];

    public function user(){
        $this->belongsTo(User::class);
    }
}
