<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Hash;

class UserRepository implements Interfaces\UserRepositoryInterface
{
    /**
     * Returns Users
     *
     * @return Collection|User[]
     */
    public function all()
    {
        return User::all()->sortBy('id');
    }

    /**
     * Returns new User
     *
     * @param UserRequest $request
     * @return Collection|User
     */
    public function create(UserRequest $request)
    {
        $request->validated();

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        $user->assignRole('Employee');
        return $user;
    }

    /**
     * Returns User by Name
     *
     * @param string $name
     * @return Collection|User
     */
    public function getByName(string $name)
    {
        return User::where('name',$name)->first();
    }
}
