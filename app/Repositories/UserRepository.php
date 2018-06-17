<?php
declare(strict_types=1);

namespace Parking\Repositories;

use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Parking\User;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserRepository {

    /** @return User[] */
    public function getAll(): Collection {
        return User::all();
    }

    /** @throws NotFoundHttpException */
    public function getById(int $id): User {
        $user = User::find($id);

        if ($user instanceof User) {
            return $user;
        }

        throw new NotFoundHttpException(
            sprintf('Can\'t find a User with id of %d', $id)
        );
    }

    /**
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function upgradeUser(int $id): User {
        $user = $this->getById($id);

        if($user->role === User::ROLE_ADMIN) {
            throw new HttpException(Response::HTTP_NOT_MODIFIED, '');
        }

        $user->role = User::ROLE_ADMIN;

        $user->save();

        return $user;
    }

}