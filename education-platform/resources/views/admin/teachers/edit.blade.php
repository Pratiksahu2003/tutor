@extends('admin.layout')

@section('title', 'Edit Teacher')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.teachers.index') }}">Teachers</a></li>
    <li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-edit me-2"></i>
            Edit Teacher: {{ $teacher->user->name ?? 'Unknown Teacher' }}
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-lg-10">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Teacher Information</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.teachers.update', $teacher ?? 1) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Teacher Profile Details -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
                                       id="specialization" name="specialization" value="{{ old('specialization', $teacher->specialization ?? '') }}">
                                @error('specialization')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="experience_years" class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
                                       id="experience_years" name="experience_years" value="{{ old('experience_years', $teacher->experience_years ?? '') }}" min="0">
                                @error('experience_years')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate (₹)</label>
                                <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
                                       id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $teacher->hourly_rate ?? '') }}" min="0">
                                @error('hourly_rate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teaching_mode" class="form-label">Teaching Mode</label>
                                <select class="form-select @error('teaching_mode') is-invalid @enderror" id="teaching_mode" name="teaching_mode">
                                    <option value="">Select Mode</option>
                                    <option value="online" {{ old('teaching_mode', $teacher->teaching_mode ?? '') == 'online' ? 'selected' : '' }}>Online</option>
                                    <option value="offline" {{ old('teaching_mode', $teacher->teaching_mode ?? '') == 'offline' ? 'selected' : '' }}>Offline</option>
                                    <option value="both" {{ old('teaching_mode', $teacher->teaching_mode ?? '') == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                                @error('teaching_mode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="bio" class="form-label">Bio</label>
                        <textarea class="form-control @error('bio') is-invalid @enderror" 
                                  id="bio" name="bio" rows="3">{{ old('bio', $teacher->bio ?? '') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="verified" name="verified" value="1" 
                                   {{ old('verified', $teacher->verified ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="verified">
                                Verified Teacher
                            </label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Teacher
                        </button>
                        <a href="{{ route('admin.teachers.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 