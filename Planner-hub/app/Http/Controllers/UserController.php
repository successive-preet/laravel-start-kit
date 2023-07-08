<?php

namespace App\Http\Controllers;

use App\Models\User;

use App\Interfaces\{UserRepositoryInterface};

class UserController extends Controller
{

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    public function index()
    {
        //$users = User::paginate();
        $users = $this->userRepository->paginate();

        return view('users.index', compact('users'));
    }
}
