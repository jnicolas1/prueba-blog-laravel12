<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $posts = Post::orderby('published_at', 'desc')
            ->where('is_published', true)
            ->paginate(5);
        return view('welcome', compact('posts'));
    }
}
