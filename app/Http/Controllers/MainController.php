<?php

namespace App\Http\Controllers;

use App\Category;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MainController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', Post::IS_PUBLIC)->paginate(2);

        return view('pages.index')->with('posts', $posts);
    }

    public function show($slug)
    {
        $post = Post::where('slug', $slug)->firstOrFail();

        return view('pages.show', compact('post'));
    }

    public function tag($slug)
    {
        $tag = Tag::where('slug', $slug)->firstOrFail();

        $posts = $tag->posts()->where('status',1)->paginate(2);

        return view('pages.list', ['posts'  =>  $posts]);
    }

    public function category($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();

        $posts = $category->posts()->paginate(2);

        return view('pages.list', ['posts'  =>  $posts]);
    }

    /**
     * Favorite a particular post
     *
     * @param  Post $post
     * @return Response
     */
    public function favoritePost(Post $post)
    {
        Auth::user()->favorites()->attach($post->id);
        return back();
    }
    /**
     * Unfavorite a particular post
     *
     * @param  Post $post
     * @return Response
     */
    public function unFavoritePost(Post $post)
    {
        Auth::user()->favorites()->detach($post->id);
        return back();
    }
}
