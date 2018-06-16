<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\User;
use Tests\TestCase;

final class EntryTest extends TestCase {
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        $user = factory(User::class)->create();
        $this->actingAs($user);
    }

    /** @testdox Given a logged in user when I request GET /api/entries/:id then it gives me the entry with the expected structure */
    public function testEntry() {
        factory(ParkingLot::class)->create();
        factory(Entry::class)->create();
        $response = $this->get('/api/entries/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'entry' => [
                'id',
                'parking_lot_id',
                'arrived_at',
                'payed_at',
                'exited_at',
                'price'
            ]
        ]);
    }

    /** @testdox Given a logged in user when I request PATCH /api/entries/:id then it gives me the updated entry with the expected structure */
    public function testEntryUpdate() {
        factory(ParkingLot::class)->create();
        factory(Entry::class)->create([
            'exited_at' => null,
            'payed_at' => null,
            'price' => null,
        ]);
        $response = $this->patch('/api/entries/1', [
            'price' => 22.4
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'entry' => [
                'id',
                'parking_lot_id',
                'arrived_at',
                'payed_at',
                'exited_at',
                'price'
            ]
        ]);
    }

    /** @testdox Given a logged in user when I request GET /api/entries/:id/price then it gives me the price for the entry */
    public function testEntryPrice() {
        factory(ParkingLot::class)->create();
        factory(Entry::class)->create([
            'exited_at' => null,
            'payed_at' => null,
            'price' => null,
        ]);
        $response = $this->get('/api/entries/1/price');

        $response->assertStatus(200);
        $response->assertJsonStructure(['price']);
    }
}