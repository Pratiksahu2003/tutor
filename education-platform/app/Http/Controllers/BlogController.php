<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class BlogController extends Controller
{
    /**
     * Display blog listing page
     */
    public function index(Request $request)
    {
        $query = BlogPost::with(['author', 'categories'])
                         ->where('status', 'published')
                         ->where('published_at', '<=', now())
                         ->orderBy('published_at', 'desc');

        // Search functionality
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('excerpt', 'like', "%{$searchTerm}%")
                  ->orWhere('content', 'like', "%{$searchTerm}%");
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        $posts = $query->paginate(12);

        // Get popular categories
        $categories = Cache::remember('blog.categories', 3600, function () {
            return BlogPost::select('categories')
                          ->whereNotNull('categories')
                          ->where('status', 'published')
                          ->get()
                          ->pluck('categories')
                          ->flatten()
                          ->unique()
                          ->take(10);
        });

        // Featured posts
        $featuredPosts = Cache::remember('blog.featured', 1800, function () {
            return BlogPost::where('featured', true)
                          ->where('status', 'published')
                          ->where('published_at', '<=', now())
                          ->orderBy('published_at', 'desc')
                          ->take(3)
                          ->get();
        });

        // Recent posts
        $recentPosts = Cache::remember('blog.recent', 1800, function () {
            return BlogPost::where('status', 'published')
                          ->where('published_at', '<=', now())
                          ->orderBy('published_at', 'desc')
                          ->take(5)
                          ->get(['id', 'title', 'slug', 'published_at', 'featured_image']);
        });

        return view('blog.index', compact('posts', 'categories', 'featuredPosts', 'recentPosts'));
    }

    /**
     * Display a single blog post
     */
    public function show($slug)
    {
        $post = BlogPost::with(['author'])
                       ->where('slug', $slug)
                       ->where('status', 'published')
                       ->where('published_at', '<=', now())
                       ->firstOrFail();

        // Increment view count
        $post->increment('views');

        // Related posts
        $relatedPosts = BlogPost::where('id', '!=', $post->id)
                              ->where('status', 'published')
                              ->where('published_at', '<=', now())
                              ->where(function ($query) use ($post) {
                                  // Match by categories if available
                                  if ($post->categories) {
                                      foreach ($post->categories as $category) {
                                          $query->orWhereJsonContains('categories', $category);
                                      }
                                  }
                              })
                              ->orderBy('published_at', 'desc')
                              ->take(3)
                              ->get();

        // If no related posts found, get recent posts
        if ($relatedPosts->count() < 3) {
            $relatedPosts = BlogPost::where('id', '!=', $post->id)
                                  ->where('status', 'published')
                                  ->where('published_at', '<=', now())
                                  ->orderBy('published_at', 'desc')
                                  ->take(3)
                                  ->get();
        }

        // Popular posts
        $popularPosts = Cache::remember('blog.popular', 3600, function () {
            return BlogPost::where('status', 'published')
                          ->where('published_at', '<=', now())
                          ->orderBy('views', 'desc')
                          ->take(5)
                          ->get(['id', 'title', 'slug', 'published_at', 'featured_image', 'views']);
        });

        return view('blog.show', compact('post', 'relatedPosts', 'popularPosts'));
    }

    /**
     * Display posts by category
     */
    public function category($category)
    {
        $posts = BlogPost::where('status', 'published')
                        ->where('published_at', '<=', now())
                        ->whereJsonContains('categories', $category)
                        ->orderBy('published_at', 'desc')
                        ->paginate(12);

        if ($posts->isEmpty()) {
            abort(404);
        }

        return view('blog.category', compact('posts', 'category'));
    }

    /**
     * Display posts by tag
     */
    public function tag($tag)
    {
        $posts = BlogPost::where('status', 'published')
                        ->where('published_at', '<=', now())
                        ->whereJsonContains('tags', $tag)
                        ->orderBy('published_at', 'desc')
                        ->paginate(12);

        if ($posts->isEmpty()) {
            abort(404);
        }

        return view('blog.tag', compact('posts', 'tag'));
    }

    /**
     * Search blogs via AJAX
     */
    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $posts = BlogPost::where('status', 'published')
                        ->where('published_at', '<=', now())
                        ->where(function ($q) use ($query) {
                            $q->where('title', 'like', "%{$query}%")
                              ->orWhere('excerpt', 'like', "%{$query}%");
                        })
                        ->orderBy('published_at', 'desc')
                        ->take(5)
                        ->get(['id', 'title', 'slug', 'excerpt', 'published_at']);

        return response()->json($posts);
    }

    /**
     * Get blog statistics for admin
     */
    public function getStats()
    {
        $stats = [
            'total_posts' => BlogPost::count(),
            'published_posts' => BlogPost::where('status', 'published')->count(),
            'draft_posts' => BlogPost::where('status', 'draft')->count(),
            'total_views' => BlogPost::sum('views'),
            'recent_posts' => BlogPost::orderBy('created_at', 'desc')->take(5)->get(),
            'popular_posts' => BlogPost::orderBy('views', 'desc')->take(5)->get(),
        ];

        return response()->json($stats);
    }
} 