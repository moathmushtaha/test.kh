<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = [
        'message',
        'status',
        'from_user_id',
        'to_user_id',
    ];

    //Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }
}
