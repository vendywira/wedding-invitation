<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventDetail extends Model
{
    protected $table = 'event_details';

    protected $fillable = [
        'event_id',
        'title',
        'event_date',
        'start_time',
        'finish_time',
        'location',
        'address',
        'google_map_link',
        'sort_order',
    ];

    protected $casts = [
        'event_date' => 'date',
        'sort_order' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    /**
     * "08:00 - 10:00" style time range, tolerating partial data.
     */
    public function getTimeRangeAttribute(): string
    {
        $start = trim((string) $this->start_time);
        $finish = trim((string) $this->finish_time);

        if ($start !== '' && $finish !== '') {
            return $start.' - '.$finish;
        }

        return $start !== '' ? $start : $finish;
    }
}
