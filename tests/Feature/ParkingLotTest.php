<?php
declare(strict_types=1);

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Entry;
use Parking\ParkingLot;
use Parking\User;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Http\Controllers\ParkingLotController */
final class ParkingLotTest extends TestCase {
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        $user = factory(User::class)->create();
        $this->actingAs($user);
    }

    /** @testdox Given a logged in user when I request GET /api/lots then it gives me the lots with the expected structure */
    public function testLots() {
        factory(ParkingLot::class, 3)->create();
        $response = $this->get('/api/lots');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'lots');
        $response->assertJsonStructure([
            'lots' => [
                [
                    'id',
                    'name',
                    'hourly_fare',
                    'capacity',
                    'taken_spots'
                ]
            ]
        ]);
    }

    /** @testdox Given a logged in user when I request GET /api/lots/1 then it gives me the requested lot with the expected structure */
    public function testLot() {
        factory(ParkingLot::class, 1)->create();

        $response = $this->get('/api/lots/1');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'lot' => [
                'id',
                'name',
                'hourly_fare',
                'capacity',
                'taken_spots'
            ]
        ]);
    }

    /** @testdox Given a logged in user when I request GET /api/lots/1/entries then it gives me the requested lot's entries with the expected structure */
    public function testLotEntries() {
        factory(ParkingLot::class, 1)->create();
        factory(Entry::class, 10)->create();

        $response = $this->get('/api/lots/1/entries');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'entries' => [
                [
                    'id',
                    'parking_lot_id',
                    'arrived_at',
                    'payed_at',
                    'exited_at',
                    'price'
                ]
            ]
        ]);
    }

    /** @testdox Given a logged in user when I request GET /api/lots/1/entries then it gives me the requested lot's entries with the expected structure */
    public function testAddLotEntry() {
        factory(ParkingLot::class, 1)->create();

        $response = $this->post('/api/lots/1/entries');

        $response->assertStatus(201);
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
}