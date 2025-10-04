<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guest_code',
        'category',
        'location',
        'max_guests',
        'is_opened'
    ];

    public function messages()
    {
        return $this->hasMany(Message::class, 'guest_code', 'guest_code');
    }
}
