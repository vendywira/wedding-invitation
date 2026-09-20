<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\Guest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StoreMessageTest extends TestCase
{
    use RefreshDatabase;

    private function makeEvent(): Event
    {
        return Event::create([
            'event_key' => 'p',
            'location' => 'Gedung',
            'event_date' => '2026-10-28',
            'start_time' => '10:00',
            'finish_time' => '13:00',
            'is_default' => true,
        ]);
    }

    public function test_wish_from_general_link_is_stored_without_guest(): void
    {
        // Link umum tidak punya baris di tabel `guests`, jadi `guest_id` kosong.
        $response = $this->postJson('/store-message', [
            'name' => 'Tamu Undangan',
            'guest_attends' => 1,
            'attendance' => 'Hadir',
            'message' => 'Selamat menempuh hidup baru!',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'name' => 'Tamu Undangan',
            'guest_id' => null,
            'message' => 'Selamat menempuh hidup baru!',
        ]);
    }

    public function test_wish_is_linked_to_guest_sent_in_the_form(): void
    {
        $event = $this->makeEvent();
        $guest = Guest::create([
            'name' => 'Budi',
            'code' => 'budi-code',
            'event_id' => $event->id,
        ]);

        $response = $this->postJson('/store-message', [
            'name' => 'Budi',
            'guest_attends' => 2,
            'attendance' => 'Hadir',
            'message' => 'Sampai jumpa di hari bahagia!',
            'guest_id' => $guest->id,
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'guest_id' => $guest->id,
            'message' => 'Sampai jumpa di hari bahagia!',
        ]);
        $this->assertDatabaseHas('guests', [
            'id' => $guest->id,
            'guest_attends' => 2,
            'attendance' => 'Hadir',
            'is_opened' => true,
        ]);
    }

    public function test_validation_error_is_returned_with_a_clear_message(): void
    {
        // Server menolak tapi harus menyertakan alasan yang jelas (Indonesia),
        // yang lalu ditampilkan form ucapan.
        $response = $this->postJson('/store-message', [
            'name' => 'Tamu Undangan',
        ]);

        $response->assertStatus(422)
            ->assertJsonPath('errors.attendance.0', 'Mohon konfirmasi kehadiran Anda terlebih dahulu.')
            ->assertJsonPath('errors.guest_attends.0', 'Jumlah tamu wajib diisi.');
    }

    public function test_wish_is_linked_via_guest_code_when_id_is_missing(): void
    {
        $event = $this->makeEvent();
        $guest = Guest::create([
            'name' => 'Sari',
            'code' => 'sari-code',
            'event_id' => $event->id,
        ]);

        $response = $this->postJson('/store-message', [
            'name' => 'Sari',
            'guest_attends' => 1,
            'attendance' => 'Tidak Hadir',
            'message' => 'Maaf belum bisa hadir.',
            'guest_code' => 'sari-code',
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('messages', [
            'guest_id' => $guest->id,
            'message' => 'Maaf belum bisa hadir.',
        ]);
    }
}
