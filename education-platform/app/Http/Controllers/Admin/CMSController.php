<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Models\BlogPost;
use App\Models\Menu;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CMSController extends Controller
{
    // =======================
    // PAGES MANAGEMENT
    // =======================
    
    public function pages(Request $request)
    {
        $query = Page::with(['author', 'parent'])
            ->withCount(['children']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by page type
        if ($request->filled('page_type')) {
            $query->where('page_type', $request->page_type);
        }
        
        // Filter by author
        if ($request->filled('author_id')) {
            $query->where('author_id', $request->author_id);
        }
        
        $pages = $query->latest()->paginate(20);
        
        return view('admin.cms.pages.index', compact('pages'));
    }
    
    public function createPage()
    {
        $parentPages = Page::whereNull('parent_id')->published()->get();
        $pageTypes = ['page', 'landing', 'about', 'contact', 'services', 'custom'];
        $templates = $this->getAvailableTemplates();
        
        return view('admin.cms.pages.create', compact('parentPages', 'pageTypes', 'templates'));
    }
    
    public function storePage(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:pages,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'page_type' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,private,password_protected',
            'password' => 'nullable|string|min:6',
            'featured' => 'boolean',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
            'template' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:pages,id',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'allow_comments' => 'boolean',
            'allow_ratings' => 'boolean',
            'is_homepage' => 'boolean',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('pages/featured', 'public');
        }
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        // Ensure slug uniqueness
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Page::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }
        
        $validated['author_id'] = Auth::id();
        
        // Handle homepage setting
        if ($validated['is_homepage'] ?? false) {
            Page::where('is_homepage', true)->update(['is_homepage' => false]);
        }
        
        $page = Page::create($validated);
        
        return redirect()->route('admin.cms.pages.show', $page)
            ->with('success', 'Page created successfully!');
    }
    
    public function showPage(Page $page)
    {
        $page->load(['author', 'updatedByUser', 'parent', 'children']);
        return view('admin.cms.pages.show', compact('page'));
    }
    
    public function editPage(Page $page)
    {
        $parentPages = Page::whereNull('parent_id')
            ->where('id', '!=', $page->id)
            ->published()
            ->get();
        $pageTypes = ['page', 'landing', 'about', 'contact', 'services', 'custom'];
        $templates = $this->getAvailableTemplates();
        
        return view('admin.cms.pages.edit', compact('page', 'parentPages', 'pageTypes', 'templates'));
    }
    
    public function updatePage(Request $request, Page $page)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('pages')->ignore($page->id)],
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'page_type' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published,private,password_protected',
            'password' => 'nullable|string|min:6',
            'featured' => 'boolean',
            'show_in_menu' => 'boolean',
            'menu_order' => 'nullable|integer|min:0',
            'template' => 'nullable|string',
            'featured_image' => 'nullable|image|max:2048',
            'parent_id' => 'nullable|exists:pages,id',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'allow_comments' => 'boolean',
            'allow_ratings' => 'boolean',
            'is_homepage' => 'boolean',
            'custom_css' => 'nullable|string',
            'custom_js' => 'nullable|string',
        ]);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($page->featured_image) {
                Storage::disk('public')->delete($page->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('pages/featured', 'public');
        }
        
        // Handle homepage setting
        if ($validated['is_homepage'] ?? false) {
            Page::where('is_homepage', true)
                ->where('id', '!=', $page->id)
                ->update(['is_homepage' => false]);
        }
        
        $validated['updated_by'] = Auth::id();
        
        $page->update($validated);
        
        return redirect()->route('admin.cms.pages.show', $page)
            ->with('success', 'Page updated successfully!');
    }
    
    public function destroyPage(Page $page)
    {
        if ($page->is_homepage) {
            return back()->with('error', 'Cannot delete the homepage!');
        }
        
        // Delete featured image
        if ($page->featured_image) {
            Storage::disk('public')->delete($page->featured_image);
        }
        
        $page->delete();
        
        return redirect()->route('admin.cms.pages.index')
            ->with('success', 'Page deleted successfully!');
    }
    
    public function publishPage(Page $page)
    {
        $page->publish();
        return back()->with('success', 'Page published successfully!');
    }
    
    // =======================
    // BLOG MANAGEMENT
    // =======================
    
    public function blogPosts(Request $request)
    {
        $query = BlogPost::with(['author']);
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%");
            });
        }
        
        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        // Filter by post type
        if ($request->filled('post_type')) {
            $query->where('post_type', $request->post_type);
        }
        
        $posts = $query->latest()->paginate(20);
        
        return view('admin.cms.blog.index', compact('posts'));
    }
    
    public function createBlogPost()
    {
        $postTypes = ['post', 'article', 'news', 'tutorial', 'guide', 'announcement'];
        $categories = $this->getBlogCategories();
        $difficultyLevels = ['beginner', 'intermediate', 'advanced', 'expert'];
        
        return view('admin.cms.blog.create', compact('postTypes', 'categories', 'difficultyLevels'));
    }
    
    public function storeBlogPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:blog_posts,slug',
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'post_type' => 'required|string',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'status' => 'required|in:draft,published,private,scheduled',
            'featured' => 'boolean',
            'sticky' => 'boolean',
            'featured_image' => 'nullable|image|max:2048',
            'video_url' => 'nullable|url',
            'categories' => 'nullable|array',
            'tags' => 'nullable|array',
            'difficulty_level' => 'nullable|string',
            'target_audience' => 'nullable|string',
            'education_level' => 'nullable|string',
            'published_at' => 'nullable|date',
            'allow_comments' => 'boolean',
            'allow_ratings' => 'boolean',
            'is_premium' => 'boolean',
            'price' => 'nullable|numeric|min:0',
        ]);
        
        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('blog/featured', 'public');
        }
        
        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['title']);
        }
        
        // Calculate reading time
        $validated['reading_time'] = $this->calculateReadingTime($validated['content']);
        $validated['word_count'] = str_word_count(strip_tags($validated['content']));
        
        $validated['author_id'] = Auth::id();
        
        $post = BlogPost::create($validated);
        
        return redirect()->route('admin.cms.blog.show', $post)
            ->with('success', 'Blog post created successfully!');
    }
    
    // =======================
    // MEDIA MANAGEMENT
    // =======================
    
    public function mediaLibrary(Request $request)
    {
        $mediaPath = storage_path('app/public/media');
        $folders = $this->getMediaFolders($mediaPath);
        $files = $this->getMediaFiles($mediaPath, $request->get('folder', ''));
        
        return view('admin.cms.media.index', compact('folders', 'files'));
    }
    
    public function uploadMedia(Request $request)
    {
        $request->validate([
            'files.*' => 'required|file|max:10240', // 10MB max
            'folder' => 'nullable|string'
        ]);
        
        $uploadedFiles = [];
        $folder = $request->get('folder', 'general');
        
        foreach ($request->file('files') as $file) {
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs("media/{$folder}", $filename, 'public');
            
            $uploadedFiles[] = [
                'name' => $filename,
                'path' => $path,
                'size' => $file->getSize(),
                'type' => $file->getMimeType(),
                'url' => Storage::url($path)
            ];
        }
        
        return response()->json([
            'success' => true,
            'files' => $uploadedFiles,
            'message' => count($uploadedFiles) . ' file(s) uploaded successfully!'
        ]);
    }
    
    // =======================
    // HELPER METHODS
    // =======================
    
    private function getAvailableTemplates()
    {
        return [
            'default' => 'Default Template',
            'landing' => 'Landing Page Template',
            'contact' => 'Contact Page Template',
            'about' => 'About Page Template',
            'services' => 'Services Template',
            'full-width' => 'Full Width Template'
        ];
    }
    
    private function getBlogCategories()
    {
        return [
            'education' => 'Education',
            'technology' => 'Technology',
            'tutorials' => 'Tutorials',
            'news' => 'News & Updates',
            'tips' => 'Tips & Tricks',
            'guides' => 'Study Guides',
            'career' => 'Career Advice'
        ];
    }
    
    private function calculateReadingTime($content)
    {
        $wordCount = str_word_count(strip_tags($content));
        return max(1, ceil($wordCount / 200)); // Average reading speed: 200 wpm
    }
    
    private function getMediaFolders($path)
    {
        if (!is_dir($path)) {
            return [];
        }
        
        $folders = [];
        $items = scandir($path);
        
        foreach ($items as $item) {
            if ($item != '.' && $item != '..' && is_dir($path . '/' . $item)) {
                $folders[] = $item;
            }
        }
        
        return $folders;
    }
    
    private function getMediaFiles($basePath, $folder = '')
    {
        $path = $folder ? $basePath . '/' . $folder : $basePath;
        
        if (!is_dir($path)) {
            return [];
        }
        
        $files = [];
        $items = scandir($path);
        
        foreach ($items as $item) {
            if ($item != '.' && $item != '..' && is_file($path . '/' . $item)) {
                $filePath = $folder ? "media/{$folder}/{$item}" : "media/{$item}";
                
                $files[] = [
                    'name' => $item,
                    'path' => $filePath,
                    'url' => Storage::url($filePath),
                    'size' => filesize($path . '/' . $item),
                    'modified' => filemtime($path . '/' . $item),
                    'type' => mime_content_type($path . '/' . $item)
                ];
            }
        }
        
        return $files;
    }
}
