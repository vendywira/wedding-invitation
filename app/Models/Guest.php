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
        'whatsapp_number',
        'attendance',
        'is_opened',
    ];

    protected $casts = [
        'is_opened' => 'boolean',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function messages()
    {
        return $this->hasMany(Message::class, 'guest_id', 'id');
    }

    /**
     * Accessor untuk format nomor WhatsApp
     */
    public function getFormattedWhatsappNumberAttribute()
    {
        if (!$this->whatsapp_number) {
            return null;
        }

        // Hilangkan semua karakter non-digit
        $number = preg_replace('/[^0-9]/', '', $this->whatsapp_number);

        // Jika sudah diawali 62, biarkan
        if (str_starts_with($number, '62')) {
            return $number;
        }

        // Jika diawali 0, ganti dengan 62
        if (str_starts_with($number, '0')) {
            return '62' . substr($number, 1);
        }

        // Jika kurang dari 10 digit, anggap sudah format yang benar
        return $number;
    }

    /**
     * Cek apakah tamu memiliki nomor WhatsApp
     */
    public function hasWhatsappNumber()
    {
        return !empty($this->whatsapp_number);
    }

    /**
     * Scope untuk tamu yang memiliki nomor WhatsApp
     */
    public function scopeWithWhatsapp($query)
    {
        return $query->whereNotNull('whatsapp_number')->where('whatsapp_number', '!=', '');
    }
}
