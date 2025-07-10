<!-- Student Profile Fields -->
<hr>
<h6 class="mb-3">Student Information</h6>
<div class="row g-3">
    <div class="col-md-6">
        <label for="date_of_birth" class="form-label">Date of Birth</label>
        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" 
               id="date_of_birth" name="date_of_birth" 
               value="{{ old('date_of_birth', $profile->date_of_birth ? $profile->date_of_birth->format('Y-m-d') : '') }}">
        @error('date_of_birth')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="gender" class="form-label">Gender</label>
        <select class="form-select @error('gender') is-invalid @enderror" id="gender" name="gender">
            <option value="">Select Gender</option>
            <option value="male" {{ old('gender', $profile->gender ?? '') === 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $profile->gender ?? '') === 'female' ? 'selected' : '' }}>Female</option>
            <option value="other" {{ old('gender', $profile->gender ?? '') === 'other' ? 'selected' : '' }}>Other</option>
        </select>
        @error('gender')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="current_class" class="form-label">Current Class/Grade</label>
        <input type="text" class="form-control @error('current_class') is-invalid @enderror" 
               id="current_class" name="current_class" 
               value="{{ old('current_class', $profile->current_class ?? '') }}"
               placeholder="e.g., Class 10, Grade 12, B.Tech 1st Year">
        @error('current_class')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="school_name" class="form-label">School/College Name</label>
        <input type="text" class="form-control @error('school_name') is-invalid @enderror" 
               id="school_name" name="school_name" 
               value="{{ old('school_name', $profile->school_name ?? '') }}"
               placeholder="Your current school or college">
        @error('school_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="board" class="form-label">Board/University</label>
        <input type="text" class="form-control @error('board') is-invalid @enderror" 
               id="board" name="board" 
               value="{{ old('board', $profile->board ?? '') }}"
               placeholder="e.g., CBSE, ICSE, State Board, University name">
        @error('board')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="area" class="form-label">Area/Locality</label>
        <input type="text" class="form-control @error('area') is-invalid @enderror" 
               id="area" name="area" 
               value="{{ old('area', $profile->area ?? '') }}"
               placeholder="Specific area or locality name">
        @error('area')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="parent_phone" class="form-label">Parent/Guardian Phone</label>
        <input type="tel" class="form-control @error('parent_phone') is-invalid @enderror" 
               id="parent_phone" name="parent_phone" 
               value="{{ old('parent_phone', $profile->parent_phone ?? '') }}"
               placeholder="Contact number for parent/guardian">
        @error('parent_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Learning Preferences -->
<hr>
<h6 class="mb-3">Learning Preferences</h6>
<div class="row g-3">
    <div class="col-md-6">
        <label for="preferred_learning_mode" class="form-label">Preferred Learning Mode</label>
        <select class="form-select @error('preferred_learning_mode') is-invalid @enderror" 
                id="preferred_learning_mode" name="preferred_learning_mode">
            <option value="">Select Learning Mode</option>
            <option value="online" {{ old('preferred_learning_mode', $profile->preferred_learning_mode ?? '') === 'online' ? 'selected' : '' }}>
                Online Only
            </option>
            <option value="offline" {{ old('preferred_learning_mode', $profile->preferred_learning_mode ?? '') === 'offline' ? 'selected' : '' }}>
                Offline Only
            </option>
            <option value="both" {{ old('preferred_learning_mode', $profile->preferred_learning_mode ?? '') === 'both' ? 'selected' : '' }}>
                Both Online & Offline
            </option>
        </select>
        @error('preferred_learning_mode')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="budget_min" class="form-label">Budget Min (₹/hour)</label>
        <input type="number" class="form-control @error('budget_min') is-invalid @enderror" 
               id="budget_min" name="budget_min" min="0" step="10"
               value="{{ old('budget_min', $profile->budget_min ?? '') }}"
               placeholder="Minimum budget">
        @error('budget_min')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-3">
        <label for="budget_max" class="form-label">Budget Max (₹/hour)</label>
        <input type="number" class="form-control @error('budget_max') is-invalid @enderror" 
               id="budget_max" name="budget_max" min="0" step="10"
               value="{{ old('budget_max', $profile->budget_max ?? '') }}"
               placeholder="Maximum budget">
        @error('budget_max')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Subjects of Interest -->
<hr>
<h6 class="mb-3">Subjects I Want to Learn</h6>
<div class="row">
    <div class="col-12">
        <div class="row g-2">
            @foreach($subjects as $subject)
                <div class="col-md-4 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               id="subject_{{ $subject->id }}" 
                               name="subjects_interested[]" 
                               value="{{ $subject->id }}"
                               {{ in_array($subject->id, $profileData['subjects_interested'] ?? []) ? 'checked' : '' }}>
                        <label class="form-check-label" for="subject_{{ $subject->id }}">
                            {{ $subject->name }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('subjects_interested')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div> 