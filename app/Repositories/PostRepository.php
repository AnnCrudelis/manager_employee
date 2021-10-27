<?php

namespace App\Repositories;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\Post;
use App\Models\User;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Database\Eloquent\Collection;

class PostRepository implements Interfaces\PostRepositoryInterface
{
    /**
     * Returns Posts
     *
     * @return Collection|Post[]
     */
    public function all()
    {
        return Post::latest()->paginate(10);
    }

    /**
     * Returns Posts by User
     *
     * @param int $id
     * @return Collection|Post[]
     */
    public function getByUser(int $id)
    {
        return Post::where('user_id', $id)->latest()->paginate(10);
    }

    /**
     * Returns Posts by Category
     *
     * @param int $category_id
     * @return Collection|Post[]
     */
    public function getByCategory(int $category_id)
    {
        return Category::find($category_id)->post()->paginate(10);
    }

    /**
     * Returns Posts by User and Category
     *
     * @param User $user
     * @param int $category_id
     * @return Collection|Post[]
     */
    public function getByCategoryByUser(User $user, int $category_id)
    {
        return Category::find($category_id)->post()->where([
            'user_id' => $user->id
        ])->paginate(10);
    }

    /**
     * Returns Post by Slug
     *
     * @param string $slug
     * @return Collection|Post
     */
    public function getBySlug(string $slug)
    {
        return Post::where('slug',$slug)->first();
    }

    /**
     * Returns updated Posts
     *
     * @param PostRequest $request
     * @param string $slug
     * @return Collection|Post[]
     */
    public function updateBySlug(PostRequest $request, string $slug)
    {
        $request->safe()->except(['image']);
        $categories [] = $request->input('category');

        $post = Post::where('slug', $slug)->first();
        $post->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
                'image_path' => $this->saveImage($request),
                'user_id' => auth()->user()->id
            ]);

        $post->category()->detach();
        foreach ($categories as $category){
            $post->category()->attach(Category::find($category));
        }
        return $post;
    }

    /**
     * Returns new Post
     *
     * @param PostRequest $request
     * @return Collection|Post
     */
    public function create(PostRequest $request)
    {
        $request->validated();
        $categories [] = $request->input('category');

        $post = Post::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'slug' => SlugService::createSlug(Post::class, 'slug', $request->title),
            'image_path' => $this->saveImage($request),
            'user_id' => auth()->user()->id
        ]);

        $post->category()->detach();
        foreach ($categories as $category){
            $post->category()->attach(Category::find($category));
        }

        return $post;
    }

    /**
     * Returns new images name by post title
     *
     * @param PostRequest $request
     * @return string
     */
    private function saveImage(PostRequest $request): string
    {
        $newImageName = uniqid() . '-' . $request->title . '.' .
            $request->image->extension();

        $request->image->move(public_path('images'),$newImageName);
        return $newImageName;
    }
}
