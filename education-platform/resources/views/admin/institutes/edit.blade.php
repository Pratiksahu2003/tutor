@extends('admin.layout')

@section('title', 'Edit Institute')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.institutes.index') }}">Institutes</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-edit me-2"></i>
            Edit Institute: {{ $institute->institute_name ?? 'Unknown Institute' }}
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Institute Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.institutes.update', $institute ?? 1) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Institute Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="institute_name" class="form-label">Institute Name</label>
                                <input type="text" class="form-control @error('institute_name') is-invalid @enderror" 
                                       id="institute_name" name="institute_name" value="{{ old('institute_name', $institute->institute_name ?? '') }}" required>
                                @error('institute_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Institute Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type">
                                    <option value="">Select Type</option>
                                    <option value="School" {{ old('type', $institute->type ?? '') == 'School' ? 'selected' : '' }}>School</option>
                                    <option value="College" {{ old('type', $institute->type ?? '') == 'College' ? 'selected' : '' }}>College</option>
                                    <option value="University" {{ old('type', $institute->type ?? '') == 'University' ? 'selected' : '' }}>University</option>
                                    <option value="Academy" {{ old('type', $institute->type ?? '') == 'Academy' ? 'selected' : '' }}>Academy</option>
                                    <option value="Coaching Center" {{ old('type', $institute->type ?? '') == 'Coaching Center' ? 'selected' : '' }}>Coaching Center</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">City</label>
                                <input type="text" class="form-control @error('city') is-invalid @enderror" 
                                       id="city" name="city" value="{{ old('city', $institute->city ?? '') }}">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="state" class="form-label">State</label>
                                <input type="text" class="form-control @error('state') is-invalid @enderror" 
                                       id="state" name="state" value="{{ old('state', $institute->state ?? '') }}">
                                @error('state')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="total_students" class="form-label">Total Students</label>
                                <input type="number" class="form-control @error('total_students') is-invalid @enderror" 
                                       id="total_students" name="total_students" value="{{ old('total_students', $institute->total_students ?? '') }}" min="0">
                                @error('total_students')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="established_year" class="form-label">Established Year</label>
                                <input type="number" class="form-control @error('established_year') is-invalid @enderror" 
                                       id="established_year" name="established_year" value="{{ old('established_year', $institute->established_year ?? '') }}" min="1900" max="{{ date('Y') }}">
                                @error('established_year')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control @error('address') is-invalid @enderror" 
                                  id="address" name="address" rows="3">{{ old('address', $institute->address ?? '') }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $institute->description ?? '') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="verified" name="verified" value="1" 
                                   {{ old('verified', $institute->verified ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="verified">
                                Verified Institute
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Institute
                        </button>
                        <a href="{{ route('admin.institutes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 