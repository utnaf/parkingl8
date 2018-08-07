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

    /** @testdox Give a non solved issue of type TYPE_LATE when I resolve it then the connected entry results exited */
    public function testPostEntryLate() {
        factory(ParkingLot::class)->create();
        factory(Entry::class)->create([
            'payed_at' => new Carbon(),
            'exited_at' => null,
            'price' => 22.3
        ]);
        factory(Issue::class)->create([
            'type' => Issue::TYPE_LATE,
            'entry_id' => 1
        ]);

        $response = $this->patch('/issues/1', [
            'action' => 'solve'
        ]);

        $response->assertStatus(302);

        $this->assertNotNull(Entry::find(1)->exited_at);
    }

    /** @testdox Given an entry with an existing issue of type T when I try to put a new issue of type T then it should not add it */
    public function testDoubleIssue() {
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

        $response = $this->patch('/api/entries/1', [
            'exited_at' => Carbon::now()
        ]);

        $response->assertStatus(406);

        $this->assertCount(1, Issue::all()->getIterator());
    }

    /** @testdox Given an entry with an existing issue of type T when I try to put a new issue of type A then it should add it */
    public function testDoubleIssueDifferentType() {
        factory(ParkingLot::class)->create();
        factory(Entry::class)->create([
            'payed_at' => null,
            'exited_at' => null,
            'price' => null
        ]);
        factory(Issue::class)->create([
            'type' => Issue::TYPE_LATE,
            'entry_id' => 1
        ]);

        $response = $this->patch('/api/entries/1', [
            'exited_at' => Carbon::now()
        ]);

        $response->assertStatus(406);

        $this->assertCount(2, Issue::all()->getIterator());
    }
}