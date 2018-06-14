<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\ParkingLot;
use Parking\User;
use Tests\TestCase;

class ParkingLotTest extends TestCase {

    use RefreshDatabase;

    const LOTS_COUNT = 3;

    /**
     * @testdox Given an anonymous user when I visit the api paths then I get redirected to the login
     * @dataProvider providePaths
     */
    public function testRedirectToLogin(string $path) {
        $response = $this->get($path);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function providePaths() {
        return [
            ['/api/lots'],
            ['/api/lots/1'],
        ];
    }

    public function testOkIfLoggedIn() {
        $user = factory(User::class)->create();
        factory(ParkingLot::class, static::LOTS_COUNT)->create();

        $this->actingAs($user);

        $response = $this->get('/api/lots');

        $response->assertStatus(200);
        $response->assertJsonCount(static::LOTS_COUNT, 'lots');
    }

}