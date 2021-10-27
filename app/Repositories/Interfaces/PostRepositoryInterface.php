<?php

namespace App\Repositories\Interfaces;

use App\Http\Requests\PostRequest;
use App\Models\User;

interface PostRepositoryInterface
{
    public function all();
    public function getByUser(int $id);
    public function getBySlug(string $slug);
    public function updateBySlug(PostRequest $request,string $slug);
    public function create(PostRequest $request);
}
