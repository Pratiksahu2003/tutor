<!-- Institute Profile Fields -->
<hr>
<h6 class="mb-3">Institute Information</h6>
<div class="row g-3">
    <div class="col-md-6">
        <label for="institute_name" class="form-label">Institute Name *</label>
        <input type="text" class="form-control @error('institute_name') is-invalid @enderror" 
               id="institute_name" name="institute_name" 
               value="{{ old('institute_name', $profile->institute_name ?? '') }}"
               placeholder="Enter your institute name">
        @error('institute_name')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="registration_number" class="form-label">Registration Number</label>
        <input type="text" class="form-control @error('registration_number') is-invalid @enderror" 
               id="registration_number" name="registration_number" 
               value="{{ old('registration_number', $profile->registration_number ?? '') }}"
               placeholder="Official registration number">
        @error('registration_number')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="contact_person" class="form-label">Contact Person</label>
        <input type="text" class="form-control @error('contact_person') is-invalid @enderror" 
               id="contact_person" name="contact_person" 
               value="{{ old('contact_person', $profile->contact_person ?? '') }}"
               placeholder="Primary contact person name">
        @error('contact_person')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="contact_phone" class="form-label">Contact Phone</label>
        <input type="tel" class="form-control @error('contact_phone') is-invalid @enderror" 
               id="contact_phone" name="contact_phone" 
               value="{{ old('contact_phone', $profile->contact_phone ?? '') }}"
               placeholder="Primary contact number">
        @error('contact_phone')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="website" class="form-label">Website</label>
        <input type="url" class="form-control @error('website') is-invalid @enderror" 
               id="website" name="website" 
               value="{{ old('website', $profile->website ?? '') }}"
               placeholder="https://your-institute-website.com">
        @error('website')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-md-6">
        <label for="established_year" class="form-label">Established Year</label>
        <input type="number" class="form-control @error('established_year') is-invalid @enderror" 
               id="established_year" name="established_year" 
               min="1800" max="{{ date('Y') }}"
               value="{{ old('established_year', $profile->established_year ?? '') }}"
               placeholder="Year of establishment">
        @error('established_year')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <div class="col-12">
        <label for="description" class="form-label">Institute Description</label>
        <textarea class="form-control @error('description') is-invalid @enderror" 
                  id="description" name="description" rows="4"
                  placeholder="Describe your institute, courses offered, achievements, and unique features...">{{ old('description', $profile->description ?? '') }}</textarea>
        @error('description')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>

<!-- Facilities -->
<hr>
<h6 class="mb-3">Available Facilities</h6>
<div class="row">
    <div class="col-12">
        @php
            $availableFacilities = [
                'library' => 'Library',
                'computer_lab' => 'Computer Lab',
                'science_lab' => 'Science Lab',
                'smart_classrooms' => 'Smart Classrooms',
                'wifi' => 'WiFi',
                'parking' => 'Parking',
                'canteen' => 'Canteen',
                'playground' => 'Playground',
                'sports_facilities' => 'Sports Facilities',
                'medical_room' => 'Medical Room',
                'transport' => 'Transport',
                'hostel' => 'Hostel',
                'ac_classrooms' => 'AC Classrooms',
                'auditorium' => 'Auditorium',
                'counseling' => 'Counseling Services',
            ];
            $instituteFacilities = $profile->facilities ?? [];
        @endphp
        
        <div class="row g-2">
            @foreach($availableFacilities as $key => $label)
                <div class="col-md-4 col-sm-6">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" 
                               id="facility_{{ $key }}" 
                               name="facilities[]" 
                               value="{{ $key }}"
                               {{ in_array($key, $instituteFacilities) ? 'checked' : '' }}>
                        <label class="form-check-label" for="facility_{{ $key }}">
                            {{ $label }}
                        </label>
                    </div>
                </div>
            @endforeach
        </div>
        @error('facilities')
            <div class="invalid-feedback d-block">{{ $message }}</div>
        @enderror
    </div>
</div> 