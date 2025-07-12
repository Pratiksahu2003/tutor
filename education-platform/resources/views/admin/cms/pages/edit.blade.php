@extends('admin.layout')

@section('title', 'Edit Page')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.pages.index') }}">CMS Pages</a></li>
    <li class="breadcrumb-item active">Edit Page</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-edit me-2"></i>
                    Edit Page: {{ $page->title }}
                </h1>
                <p class="text-muted">Update page content and settings</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.pages.show', $page) }}" class="btn btn-outline-info me-2">
                    <i class="fas fa-eye me-2"></i>View Page
                </a>
                <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Pages
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Page Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cms.pages.update', $page) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <!-- Basic Information -->
                            <div class="mb-3">
                                <label for="title" class="form-label">Page Title *</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title', $page->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="slug" class="form-label">URL Slug</label>
                                <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                                       id="slug" name="slug" value="{{ old('slug', $page->slug) }}">
                                @error('slug')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="excerpt" class="form-label">Excerpt</label>
                                <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                          id="excerpt" name="excerpt" rows="3">{{ old('excerpt', $page->excerpt) }}</textarea>
                                @error('excerpt')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="content" class="form-label">Content *</label>
                                <textarea class="form-control @error('content') is-invalid @enderror" 
                                          id="content" name="content" rows="10" required>{{ old('content', $page->content) }}</textarea>
                                @error('content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <!-- Page Settings -->
                            <div class="mb-3">
                                <label for="page_type" class="form-label">Page Type *</label>
                                <select class="form-select @error('page_type') is-invalid @enderror" 
                                        id="page_type" name="page_type" required>
                                    <option value="">Select Type</option>
                                    @foreach($pageTypes as $type)
                                        <option value="{{ $type }}" {{ old('page_type', $page->page_type) == $type ? 'selected' : '' }}>
                                            {{ ucfirst($type) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('page_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft" {{ old('status', $page->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ old('status', $page->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="private" {{ old('status', $page->status) == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="parent_id" class="form-label">Parent Page</label>
                                <select class="form-select @error('parent_id') is-invalid @enderror" 
                                        id="parent_id" name="parent_id">
                                    <option value="">No Parent</option>
                                    @foreach($parentPages as $parentPage)
                                        <option value="{{ $parentPage->id }}" {{ old('parent_id', $page->parent_id) == $parentPage->id ? 'selected' : '' }}>
                                            {{ $parentPage->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="template" class="form-label">Template</label>
                                <select class="form-select @error('template') is-invalid @enderror" 
                                        id="template" name="template">
                                    <option value="">Default Template</option>
                                    @foreach($templates as $template)
                                        <option value="{{ $template }}" {{ old('template', $page->template) == $template ? 'selected' : '' }}>
                                            {{ ucfirst($template) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('template')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="featured_image" class="form-label">Featured Image</label>
                                @if($page->featured_image)
                                    <div class="mb-2">
                                        <img src="{{ Storage::url($page->featured_image) }}" alt="Current featured image" class="img-thumbnail" style="max-width: 200px;">
                                    </div>
                                @endif
                                <input type="file" class="form-control @error('featured_image') is-invalid @enderror" 
                                       id="featured_image" name="featured_image" accept="image/*">
                                @error('featured_image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Options -->
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Page Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" 
                                               {{ old('featured', $page->featured) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="featured">
                                            Featured Page
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_in_menu" name="show_in_menu" value="1" 
                                               {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_in_menu">
                                            Show in Menu
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_homepage" name="is_homepage" value="1" 
                                               {{ old('is_homepage', $page->is_homepage) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_homepage">
                                            Set as Homepage
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1" 
                                               {{ old('allow_comments', $page->allow_comments) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_comments">
                                            Allow Comments
                                        </label>
                                    </div>

                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="allow_ratings" name="allow_ratings" value="1" 
                                               {{ old('allow_ratings', $page->allow_ratings) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="allow_ratings">
                                            Allow Ratings
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- SEO -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="mb-0">SEO Settings</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="meta_title" class="form-label">Meta Title</label>
                                        <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                               value="{{ old('meta_title', $page->meta_title) }}">
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_description" class="form-label">Meta Description</label>
                                        <textarea class="form-control" id="meta_description" name="meta_description" 
                                                  rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                                        <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" 
                                               value="{{ old('meta_keywords', $page->meta_keywords) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.cms.pages.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Update Page
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Auto-generate slug from title if empty
    $('#title').on('input', function() {
        if (!$('#slug').val()) {
            let slug = $(this).val()
                .toLowerCase()
                .replace(/[^a-z0-9 -]/g, '')
                .replace(/\s+/g, '-')
                .replace(/-+/g, '-');
            $('#slug').val(slug);
        }
    });
});
</script>
@endpush 