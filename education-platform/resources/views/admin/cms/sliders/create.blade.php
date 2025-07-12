@extends('admin.layout')

@section('title', 'Create New Slider')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.cms.sliders.index') }}">Sliders</a></li>
    <li class="breadcrumb-item active">Create Slider</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-plus me-2"></i>
                    Create New Slider
                </h1>
                <p class="text-muted">Add a new slider/carousel</p>
            </div>
            <div>
                <a href="{{ route('admin.cms.sliders.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Sliders
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Slider Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.cms.sliders.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Slider Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="mb-0">Slider Options</h6>
                                </div>
                                <div class="card-body">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                               {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Active Slider
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="autoplay" name="autoplay" value="1" 
                                               {{ old('autoplay', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="autoplay">
                                            Enable Autoplay
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_arrows" name="show_arrows" value="1" 
                                               {{ old('show_arrows', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_arrows">
                                            Show Navigation Arrows
                                        </label>
                                    </div>
                                    
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" id="show_dots" name="show_dots" value="1" 
                                               {{ old('show_dots', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="show_dots">
                                            Show Dots Indicator
                                        </label>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="autoplay_speed" class="form-label">Autoplay Speed (ms)</label>
                                        <input type="number" class="form-control @error('autoplay_speed') is-invalid @enderror" 
                                               id="autoplay_speed" name="autoplay_speed" 
                                               value="{{ old('autoplay_speed', 3000) }}" min="1000" max="10000">
                                        @error('autoplay_speed')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-end">
                                <a href="{{ route('admin.cms.sliders.index') }}" class="btn btn-secondary me-2">Cancel</a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Create Slider
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