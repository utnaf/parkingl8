<?php
declare(strict_types=1);

namespace Parking\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Parking\Repositories\UserRepository;
use Symfony\Component\HttpKernel\Exception\HttpException;

/** @codeCoverageIgnore */
final class UserController extends Controller {

    public function index(UserRepository $userRepository) {
        return view('users.list', [
            'users' => $userRepository->getAll()
        ]);
    }

    public function update(Request $request, string $id, UserRepository $userRepository) {
        if($request->get('action') !== 'upgrade') {
            return redirect()->back();
        }

        try {
            $userRepository->upgradeUser((int) $id);
        } catch (HttpException $e) {
            abort($e->getStatusCode(), $e->getMessage());
        }

        return Redirect::route('user.list')->with('success', true);
    }

}