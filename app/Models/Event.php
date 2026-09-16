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
        'title',
        'location',
        'address',
        'event_date',
        'start_time',
        'finish_time',
        'google_map_link',
    ];

    /**
     * Human readable label for each event key.
     */
    public function getLabelAttribute(): string
    {
        return match ($this->event_key) {
            'gedung' => 'Resepsi (Gedung)',
            'rumah' => 'Akad / Rumah',
            default => ucfirst((string) $this->event_key),
        };
    }

    /**
     * "08:00 - 10:00" style time range for the primary ceremony.
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

    /**
     * Extra ceremonies shown after the primary one in the invitation.
     */
    public function details()
    {
        return $this->hasMany(EventDetail::class, 'event_id')->orderBy('sort_order')->orderBy('id');
    }

    /**
     * Primary ceremony first, then every additional ceremony.
     *
     * @return array<int, array<string, mixed>>
     */
    public function allCeremonies(?string $fallbackTitle = null): array
    {
        $primaryTitle = $this->title ?: ($fallbackTitle ?: $this->label);

        $ceremonies = [[
            'title' => $primaryTitle,
            'event_date' => $this->event_date,
            'start_time' => $this->start_time,
            'finish_time' => $this->finish_time,
            'location' => $this->location,
            'address' => $this->address,
            'google_map_link' => $this->google_map_link,
        ]];

        foreach ($this->details as $detail) {
            $ceremonies[] = [
                'title' => $detail->title,
                'event_date' => $detail->event_date,
                'start_time' => $detail->start_time,
                'finish_time' => $detail->finish_time,
                'location' => $detail->location,
                'address' => $detail->address,
                'google_map_link' => $detail->google_map_link,
            ];
        }

        return $ceremonies;
    }

    public function guests()
    {
        return $this->hasMany(Guest::class, 'event_id', 'id');
    }
}
