<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'event_id',
        'guest_attends',
        'attendance',
        'is_opened',
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'guest_id', 'id');
    }
}
