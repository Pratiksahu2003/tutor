@extends('admin.layout')

@section('title', 'Edit Permission')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.permissions.index') }}">Permissions</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-edit me-2"></i>
            Edit Permission: {{ $permission->name ?? 'Unknown Permission' }}
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Permission Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.permissions.update', $permission ?? 1) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Permission Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $permission->name ?? '') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" name="slug" value="{{ old('slug', $permission->slug ?? '') }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $permission->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select @error('category') is-invalid @enderror" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <option value="User Management" {{ old('category', $permission->category ?? '') == 'User Management' ? 'selected' : '' }}>User Management</option>
                                    <option value="Teacher Management" {{ old('category', $permission->category ?? '') == 'Teacher Management' ? 'selected' : '' }}>Teacher Management</option>
                                    <option value="Institute Management" {{ old('category', $permission->category ?? '') == 'Institute Management' ? 'selected' : '' }}>Institute Management</option>
                                    <option value="Subject Management" {{ old('category', $permission->category ?? '') == 'Subject Management' ? 'selected' : '' }}>Subject Management</option>
                                    <option value="System Management" {{ old('category', $permission->category ?? '') == 'System Management' ? 'selected' : '' }}>System Management</option>
                                </select>
                                @error('category')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="module" class="form-label">Module</label>
                                <input type="text" class="form-control @error('module') is-invalid @enderror" 
                                       id="module" name="module" value="{{ old('module', $permission->module ?? '') }}">
                                @error('module')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Permission
                        </button>
                        <a href="{{ route('admin.permissions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 