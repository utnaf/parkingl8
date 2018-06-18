<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

final class AuthTest extends TestCase {
    /**
     * @testdox Given an anonymous user when I visit the api paths then I get redirected to the login
     * @dataProvider providePaths
     */
    public function testRedirectToLogin(string $method, string $path) {
        $response = $this->{$method}($path);

        $response->assertStatus(302);
        $response->assertRedirect('login');
    }

    public function providePaths() {
        return [
            'GET lots' => ['get', '/api/lots'],
            'GET single lot' => ['get', '/api/lots/1'],
            'GET lot entries' => ['get', '/api/lots/1/entries'],
            'POST entry' => ['post', '/api/lots/1/entries'],
            'GET entry' => ['get', '/api/entries/1'],
            'GET entry price' => ['get', '/api/entries/1/price'],
            'PATH entry' => ['patch', '/api/entries/1'],
            'GET dashboard' => ['get', '/dashboard'],
            'GET issues' => ['get', '/issues'],
            'PATH issues' => ['patch', '/issues/1'],
            'GET locale' => ['get', '/locale/it'],
            'GET lot' => ['get', '/lots/1'],
            'POST lots' => ['post', '/lots/1'],
            'GET users' => ['get', '/users'],
            'PATH users' => ['patch', '/users/1'],
        ];
    }

}