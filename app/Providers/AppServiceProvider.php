<?php

namespace App\Providers;

use App\Category;
use App\Post;
use App\User;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('pages._sidebar', function($view){
            $view->with('popularPosts', Post::getPopularPosts());
            $view->with('featuredPosts', Post::where('is_featured', 1)->take(3)->get());
            $view->with('recentPosts', Post::orderBy('date', 'desc')->take(4)->get());
            $view->with('categories', Category::all());
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
