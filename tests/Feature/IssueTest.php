<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Parking\Entry;
use Parking\Issue;
use Parking\ParkingLot;
use Parking\User;
use Tests\TestCase;

final class IssueTest extends TestCase {
    use RefreshDatabase;

    public function setUp() {
        parent::setUp();
        $user = factory(User::class)->create();
        $this->actingAs($user);
    }

    /** @testdox Give a non solved issue of type TYPE_NOT_PAYED when I resolve it then the connected entry results payed */
    public function testPostNotPayed() {
        factory(ParkingLot::class)->create();
        factory(Entry::class)->create([
            'payed_at' => null,
            'exited_at' => null,
            'price' => null
        ]);
        factory(Issue::class)->create([
            'type' => Issue::TYPE_NOT_PAYED,
            'entry_id' => 1
        ]);

        $response = $this->patch('/issues/1', [
            'action' => 'solve'
        ]);

        $response->assertStatus(302);

        $this->assertNotNull(Entry::find(1)->payed_at);
        $this->assertNotNull(Entry::find(1)->price);
    }
}