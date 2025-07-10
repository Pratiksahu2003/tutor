<!-- Teacher Profile Fields -->
<hr>
<h6 class="mb-3">Teaching Information</h6>
<div class="row g-3">
    <div class="col-md-6">
        <label for="qualification" class="form-label">Qualification</label>
        <input type="text" class="form-control @error('qualification') is-invalid @enderror" 
               id="qualification" name="qualification" 
               value="{{ old('qualification', $profile->qualification ?? '') }}"
               placeholder="e.g., M.Sc Mathematics, B.Tech Computer Science">
        @error('qualification')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="experience_years" class="form-label">Years of Experience</label>
        <input type="number" class="form-control @error('experience_years') is-invalid @enderror" 
               id="experience_years" name="experience_years" min="0" max="50"
               value="{{ old('experience_years', $profile->experience_years ?? '') }}">
        @error('experience_years')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="specialization" class="form-label">Specialization</label>
        <input type="text" class="form-control @error('specialization') is-invalid @enderror" 
               id="specialization" name="specialization" 
               value="{{ old('specialization', $profile->specialization ?? '') }}"
               placeholder="e.g., Advanced Mathematics, JEE Preparation">
        @error('specialization')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="hourly_rate" class="form-label">Hourly Rate (₹)</label>
        <input type="number" class="form-control @error('hourly_rate') is-invalid @enderror" 
               id="hourly_rate" name="hourly_rate" min="0" step="10"
               value="{{ old('hourly_rate', $profile->hourly_rate ?? '') }}">
        @error('hourly_rate')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="teaching_mode" class="form-label">Teaching Mode</label>
        <select class="form-select @error('teaching_mode') is-invalid @enderror" 
                id="teaching_mode" name="teaching_mode">
            <option value="">Select Teaching Mode</option>
            <option value="online" {{ old('teaching_mode', $profile->teaching_mode ?? '') === 'online' ? 'selected' : '' }}>
                Online Only
            </option>
            <option value="offline" {{ old('teaching_mode', $profile->teaching_mode ?? '') === 'offline' ? 'selected' : '' }}>
                Offline Only
            </option>
            <option value="both" {{ old('teaching_mode', $profile->teaching_mode ?? '') === 'both' ? 'selected' : '' }}>
                Both Online & Offline
            </option>
        </select>
        @error('teaching_mode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="bio" class="form-label">About Me</label>
        <textarea class="form-control @error('bio') is-invalid @enderror" 
                  id="bio" name="bio" rows="3"
                  placeholder="Tell students about your teaching experience, methodology, and achievements...">{{ old('bio', $profile->bio ?? '') }}</textarea>
        @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Teaching Location -->
<hr>
<h6 class="mb-3">Teaching Location Preferences</h6>
<div class="row g-3">
    <div class="col-md-4">
        <label for="teaching_city" class="form-label">Teaching City</label>
        <input type="text" class="form-control @error('teaching_city') is-invalid @enderror" 
               id="teaching_city" name="teaching_city" 
               value="{{ old('teaching_city', $profile->teaching_city ?? '') }}"
               placeholder="Primary city for teaching">
        @error('teaching_city')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="teaching_state" class="form-label">Teaching State</label>
        <input type="text" class="form-control @error('teaching_state') is-invalid @enderror" 
               id="teaching_state" name="teaching_state" 
               value="{{ old('teaching_state', $profile->teaching_state ?? '') }}">
        @error('teaching_state')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-4">
        <label for="teaching_area" class="form-label">Specific Area/Locality</label>
        <input type="text" class="form-control @error('teaching_area') is-invalid @enderror" 
               id="teaching_area" name="teaching_area" 
               value="{{ old('teaching_area', $profile->teaching_area ?? '') }}"
               placeholder="e.g., Koramangala, Indiranagar">
        @error('teaching_area')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="travel_radius_km" class="form-label">Willing to Travel (km)</label>
        <input type="number" class="form-control @error('travel_radius_km') is-invalid @enderror" 
               id="travel_radius_km" name="travel_radius_km" min="0" max="100" step="0.5"
               value="{{ old('travel_radius_km', $profile->travel_radius_km ?? '10') }}">
        <small class="text-muted">Maximum distance you're willing to travel for home tuitions</small>
        @error('travel_radius_km')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Subjects -->
<hr>
<h6 class="mb-3">Subjects I Teach</h6>
<div class="row">
    <div class="col-12">
        <div class="row g-2">
            @foreach($subjects as $subject)
                <div class="col-md-4 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               id="subject_{{ $subject->id }}" 
                               name="subjects[]" 
                               value="{{ $subject->id }}"
                               {{ in_array($subject->id, $profileData['subjects'] ?? []) ? 'checked' : '' }}>
                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                            {{ $subject->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('subjects')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div> 