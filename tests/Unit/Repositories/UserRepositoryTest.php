<?php
declare(strict_types=1);

namespace Tests\Unit\Repositories;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Parking\Repositories\UserRepository;
use Parking\User;
use Tests\TestCase;

/** @coversDefaultClass \Parking\Repositories\UserRepository */
final class UserRepositoryTest extends TestCase {
    use RefreshDatabase;

    /** @testdox Given 4 non-deleted users in the db when getAll is called then 4 users are returned */
    public function testGetAll() {
        factory(User::class, 4)->create();

        $repository = new UserRepository;

        $this->assertCount(4, $repository->getAll());
    }

    /** @testdox Give a non deleted user in the db when getById is called the user is returned */
    public function testGetById() {
        $user = factory(User::class)->create();

        $repository = new UserRepository;

        $this->assertEquals($user->id, $repository->getById(1)->id);
    }

    /**
     * @testdox When getById is called for a non existing id then it should throw the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function testGetByIdException() {
        $repository = new UserRepository;

        $repository->getById(1);
    }

    /** @testdox Given a user with USER role when I upgrade it then it has ADMIN role */
    public function testUpgradeUser() {
        /** @var User $user */
        $user = factory(User::class)->create();
        $user->refresh();

        $repository = new UserRepository;

        $this->assertEquals(User::ROLE_USER, $user->role);
        $updatedUser = $repository->upgradeUser(1);
        $this->assertEquals(User::ROLE_ADMIN, $updatedUser->role);
    }

    /**
     * @testdox Give a user with ADMIN role when I upgrade the it thorws the correct exception
     * @expectedException \Symfony\Component\HttpKernel\Exception\HttpException
     */
    public function testUpgradeUserNotChange() {
        factory(User::class)->create([
            'role' => User::ROLE_ADMIN
        ]);

        $repository = new UserRepository;
        $repository->upgradeUser(1);
    }
}