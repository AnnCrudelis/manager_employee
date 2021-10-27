<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\PostRequest;
use App\Http\Requests\UserRequest;
use App\Models\User;

interface UserRepositoryInterface
{
    public function all();
    public function create(UserRequest $request);
    public function getByName(string $name);
}
