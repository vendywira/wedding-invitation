<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Event extends Model
{
    use HasFactory;

    protected $table = 'events';

    protected $fillable = [
        'event_key',
        'group_name',
        'is_default',
        'sort_order',
        'title',
        'location',
        'address',
        'event_date',
        'start_time',
        'finish_time',
        'google_map_link',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * One `events` row is one invitation group:
     *
     * - `event_key`  → the public URL slug     (/<event_key>/invitation)
     * - `group_name` → the label the admin sees in the dashboard
     * - `details`    → extra ceremonies listed on top of the primary one
     * - guests       → the guest list that receives this exact invitation
     *
     * Groups are no longer limited to "gedung"/"rumah": the admin can create as
     * many as needed and point each of them at a different audience.
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, Event>
     */
    public static function groups()
    {
        return static::query()
            ->with('details')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * The group rendered by the public, un-targeted `/invitation` link.
     */
    public static function defaultGroup(): ?self
    {
        return static::query()->where('is_default', true)->first()
            ?? static::query()->orderBy('sort_order')->orderBy('id')->first();
    }

    /**
     * Make this group the one the public `/invitation` link renders.
     */
    public function makeDefault(): void
    {
        static::query()->where('id', '!=', $this->id)->update(['is_default' => false]);
        $this->forceFill(['is_default' => true])->save();
    }

    /**
     * Human readable label for the group shown throughout the dashboard.
     */
    public function getLabelAttribute(): string
    {
        if (filled($this->group_name)) {
            return (string) $this->group_name;
        }

        if (filled($this->title)) {
            return (string) $this->title;
        }

        return Str::headline((string) $this->event_key);
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
     * Full invitation link for this group, optionally personalised per guest.
     */
    public function publicUrl(?string $guestName = null): string
    {
        $url = url('/'.$this->event_key.'/invitation');

        return $guestName ? $url.'?to='.urlencode($guestName) : $url;
    }

    public function getGuestCountAttribute(): int
    {
        return $this->guests()->count();
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
