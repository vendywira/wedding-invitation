<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'event_key',
        'location',
        'event_date',
        'start_time',
        'finish_time',
        'google_map_link',
    ];
}
