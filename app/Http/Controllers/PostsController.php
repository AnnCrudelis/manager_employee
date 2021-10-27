<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Category;
use App\Models\User;
use App\Repositories\PostRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class PostsController extends Controller
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $user = Auth::user();
        if ($user == null)
            return view('index')->with('message','YOU MUST BE AUTHENTICATED');


        if ($user->hasRole('Manager')) {
            $items = $this->postRepository->all();
        } else {
            $items = $this->postRepository->getByUser($user->id);
        }
        return view('blog.index')->with('posts', $items);
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $category_id
     * @return Application|Factory|View
     */
    public function getByCategory(int $category_id)
    {
        $user = Auth::user();
        if ($user == null)
            return view('index')->with('message','YOU MUST BE AUTHENTICATED');

        if ($user->hasRole('Manager')) {
            $items = $this->postRepository->getByCategory($category_id);
        } else {
            $items = $this->postRepository->getByCategoryByUser($user->id, $category_id);
        }
        return view('blog.index')->with('posts', $items);
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function getByUser(int $id)
    {
        $this_user = Auth::user();
        if ($this_user->hasRole('Manager')) {
            $items = $this->postRepository->getByUser($id);
        }
        return view('blog.index')->with('posts', $items);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('blog.create')->with('categories', Category::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PostRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(PostRequest $request)
    {
        $this->postRepository->create($request);

        return redirect('/blog')
            ->with('message', 'Your post has been added!');
    }

    /**
     * Display the specified resource.
     *
     * @param string $slug
     * @return Application|Factory|View
     */
    public function show(string $slug)
    {
        return view('blog.show')->with([
            'post' => $this->postRepository->getBySlug($slug),
            'categories' => Category::all()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param string $slug
     * @return Application|Factory|View
     */
    public function edit(string $slug)
    {
        return view('blog.edit')->with([
            'post' => $this->postRepository->getBySlug($slug),
            'categories' => Category::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param PostRequest $request
     * @param string $slug
     * @return Application|Redirector|RedirectResponse
     */
    public function update(PostRequest $request, string $slug)
    {
        $this->postRepository->updateBySlug($request,$slug);

        return redirect('/blog')
            ->with('message', 'Your post has been updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param string $slug
     * @return Application|Redirector|RedirectResponse
     */
    public function destroy(string $slug)
    {
        $post = $this->postRepository->getBySlug($slug);
        $post->category()->detach();
        $post->delete();

        return redirect('/blog')
            ->with('message', 'Your post has been deleted!');
    }
}
