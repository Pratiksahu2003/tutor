@extends('layouts.dashboard')

@section('title', 'Teacher Profile')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                @csrf
                @method('PUT')

                <!-- Profile Image Section -->
                <div class="card mb-4">
                    <div class="card-header bg-primary text-white d-flex align-items-center">
                        <i class="bi bi-person-circle me-2"></i> Profile Image
                    </div>
                    <div class="card-body d-flex align-items-center">
                        <div class="me-4">
                            @if(auth()->user()->profile_image)
                                <img src="{{ Storage::url(auth()->user()->profile_image) }}" alt="Profile" class="rounded-circle border border-2" style="width: 100px; height: 100px; object-fit: cover;">
                            @else
                                <i class="bi bi-person-circle text-secondary" style="font-size: 100px;"></i>
                            @endif
                        </div>
                        <div class="flex-fill">
                            <div class="mb-2">
                                <label class="form-label">Upload Profile Photo</label>
                                <input type="file" name="profile_image" accept="image/*" class="form-control">
                                <div class="form-text">Upload a professional photo (max 2MB). Recommended size: 400x400 pixels.</div>
                                @error('profile_image')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Basic Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-success text-white d-flex align-items-center">
                        <i class="bi bi-info-circle me-2"></i> Basic Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="qualification" class="form-label">Qualification <span class="text-danger">*</span></label>
                                <input type="text" name="qualification" id="qualification" value="{{ old('qualification', $teacherProfile->qualification) }}" class="form-control" required placeholder="e.g., M.Tech Computer Science, B.Ed Mathematics">
                                @error('qualification')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="experience_years" class="form-label">Years of Experience <span class="text-danger">*</span></label>
                                <input type="number" name="experience_years" id="experience_years" value="{{ old('experience_years', $teacherProfile->experience_years) }}" min="0" max="50" class="form-control" required placeholder="e.g., 5">
                                @error('experience_years')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="specialization" class="form-label">Specialization</label>
                                <input type="text" name="specialization" id="specialization" value="{{ old('specialization', $teacherProfile->specialization) }}" class="form-control" placeholder="e.g., Advanced Mathematics, Physics, Chemistry">
                                @error('specialization')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="hourly_rate" class="form-label">Hourly Rate (₹)</label>
                                <div class="input-group">
                                    <span class="input-group-text">₹</span>
                                    <input type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $teacherProfile->hourly_rate) }}" min="0" step="0.01" class="form-control" placeholder="500">
                                </div>
                                @error('hourly_rate')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea name="bio" id="bio" rows="3" class="form-control" placeholder="Tell students about your teaching experience, methodology, and what makes you unique...">{{ old('bio', $teacherProfile->bio) }}</textarea>
                                <div class="form-text">Maximum 1000 characters</div>
                                @error('bio')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Teaching Preferences Section -->
                <div class="card mb-4">
                    <div class="card-header bg-warning text-dark d-flex align-items-center">
                        <i class="bi bi-gear me-2"></i> Teaching Preferences
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="teaching_mode" class="form-label">Teaching Mode <span class="text-danger">*</span></label>
                                <select name="teaching_mode" id="teaching_mode" class="form-select" required>
                                    <option value="">Select teaching mode</option>
                                    <option value="online" {{ old('teaching_mode', $teacherProfile->teaching_mode) == 'online' ? 'selected' : '' }}>Online Only</option>
                                    <option value="offline" {{ old('teaching_mode', $teacherProfile->teaching_mode) == 'offline' ? 'selected' : '' }}>Offline Only</option>
                                    <option value="both" {{ old('teaching_mode', $teacherProfile->teaching_mode) == 'both' ? 'selected' : '' }}>Both Online & Offline</option>
                                </select>
                                @error('teaching_mode')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="employment_type" class="form-label">Employment Type <span class="text-danger">*</span></label>
                                <select name="employment_type" id="employment_type" class="form-select" required>
                                    <option value="">Select employment type</option>
                                    <option value="freelance" {{ old('employment_type', $teacherProfile->employment_type) == 'freelance' ? 'selected' : '' }}>Freelance</option>
                                    <option value="institute" {{ old('employment_type', $teacherProfile->employment_type) == 'institute' ? 'selected' : '' }}>Institute</option>
                                    <option value="both" {{ old('employment_type', $teacherProfile->employment_type) == 'both' ? 'selected' : '' }}>Both</option>
                                </select>
                                @error('employment_type')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="availability" class="form-label">Availability</label>
                                <input type="text" name="availability" id="availability" value="{{ old('availability', $teacherProfile->availability) }}" class="form-control" placeholder="e.g., Weekdays 6-8 PM, Weekends 10 AM-2 PM">
                                @error('availability')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="languages" class="form-label">Languages</label>
                                <input type="text" name="languages[]" id="languages" value="{{ old('languages', is_array($teacherProfile->languages) ? implode(', ', $teacherProfile->languages) : $teacherProfile->languages) }}" class="form-control" placeholder="e.g., English, Hindi, Spanish">
                                @error('languages')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Exam Preparation Packages Section -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-journal-text me-2"></i> Exam Preparation Packages</span>
                        <button type="button" class="btn btn-primary btn-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addPackageModal">
                            <i class="bi bi-plus-circle me-1"></i> Add Package
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="selected-packages-list">
                            @php
                                $selectedPackages = old('exam_packages', $teacherProfile->examPackages->pluck('id')->toArray() ?? []);
                                $packageDurations = old('exam_package_durations') ?? $teacherProfile->examPackages->pluck('pivot.duration', 'id')->toArray();
                                $packagePrices = old('exam_package_prices') ?? $teacherProfile->examPackages->pluck('pivot.price', 'id')->toArray();
                            @endphp
                            @foreach($selectedPackages as $i => $pkgId)
                                @php
                                    $pkg = $examPackages->firstWhere('id', $pkgId);
                                    $duration = is_array($packageDurations) && array_key_exists($i, $packageDurations) ? $packageDurations[$i] : ($packageDurations[$pkgId] ?? '');
                                    $price = is_array($packagePrices) && array_key_exists($i, $packagePrices) ? $packagePrices[$i] : ($packagePrices[$pkgId] ?? '');
                                @endphp
                                @if($pkg)
                                <div class="row align-items-end mb-2 package-row" data-package-id="{{ $pkg->id }}">
                                    <div class="col-md-4">
                                        <input type="hidden" name="exam_packages[]" value="{{ $pkg->id }}">
                                        <input type="text" class="form-control" value="{{ $pkg->name }}" readonly>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="exam_package_durations[]" class="form-control" placeholder="Duration (min)" value="{{ $duration }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="number" name="exam_package_prices[]" class="form-control" placeholder="Price" value="{{ $price }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger btn-sm remove-package-btn"><i class="bi bi-x"></i></button>
                                    </div>
                                </div>
                                @endif
                            @endforeach
                        </div>
                        <div class="form-text">Add packages and specify price/duration for each.</div>
                    </div>
                </div>

                <!-- Modal for adding packages -->
                <div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="addPackageModalLabel">Add Exam Package</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                      </div>
                      <div class="modal-body">
                        <select id="available-packages" class="form-select">
                            <option value="">Select Package</option>
                            @foreach($examPackages as $pkg)
                                @if(!in_array($pkg->id, $selectedPackages))
                                    <option value="{{ $pkg->id }}">{{ $pkg->name }}</option>
                                @endif
                            @endforeach
                        </select>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="add-selected-package">Add</button>
                      </div>
                    </div>
                  </div>
                </div>

                @push('scripts')
                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // Add package
                    document.getElementById('add-selected-package').addEventListener('click', function() {
                        var select = document.getElementById('available-packages');
                        var pkgId = select.value;
                        var pkgName = select.options[select.selectedIndex].text;
                        if(pkgId && !document.querySelector('.package-row[data-package-id="'+pkgId+'"]')) {
                            var row = document.createElement('div');
                            row.className = 'row align-items-end mb-2 package-row';
                            row.setAttribute('data-package-id', pkgId);
                            row.innerHTML = `
                                <div class=\"col-md-4\">
                                    <input type=\"hidden\" name=\"exam_packages[]\" value=\"${pkgId}\">
                                    <input type=\"text\" class=\"form-control\" value=\"${pkgName}\" readonly>
                                </div>
                                <div class=\"col-md-3\">
                                    <input type=\"number\" name=\"exam_package_durations[]\" class=\"form-control\" placeholder=\"Duration (min)\">
                                </div>
                                <div class=\"col-md-3\">
                                    <input type=\"number\" name=\"exam_package_prices[]\" class=\"form-control\" placeholder=\"Price\">
                                </div>
                                <div class=\"col-md-2\">
                                    <button type=\"button\" class=\"btn btn-danger btn-sm remove-package-btn\"><i class=\"bi bi-x\"></i></button>
                                </div>
                            `;
                            document.getElementById('selected-packages-list').appendChild(row);
                            select.querySelector('option[value="'+pkgId+'"]').disabled = true;
                            select.value = '';
                            var modal = bootstrap.Modal.getInstance(document.getElementById('addPackageModal'));
                            modal.hide();
                        }
                    });

                    // Remove package
                    document.getElementById('selected-packages-list').addEventListener('click', function(e) {
                        if(e.target.closest('.remove-package-btn')) {
                            var row = e.target.closest('.package-row');
                            var pkgId = row.getAttribute('data-package-id');
                            row.remove();
                            document.querySelector('#available-packages option[value="'+pkgId+'"]')?.removeAttribute('disabled');
                        }
                    });
                });
                </script>
                @endpush

                <!-- Subjects & Rates Section -->
                <div class="card mb-4">
                    <div class="card-header bg-info text-white d-flex align-items-center justify-content-between">
                        <span><i class="bi bi-journal-text me-2"></i> Subjects & Rates</span>
                        <button type="button" id="add-subject" class="btn btn-primary btn-sm d-flex align-items-center">
                            <i class="bi bi-plus-circle me-1"></i> Add Subject
                        </button>
                    </div>
                    <div class="card-body">
                        <div id="subjects-container" class="row g-3">
                            @if($subjects->count() > 0)
                                @foreach($teacherSubjects as $index => $subjectId)
                                    <div class="col-12 subject-item">
                                        <div class="card position-relative mb-2">
                                            <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 remove-subject" title="Remove Subject">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                            <div class="card-body row g-3 align-items-end">
                                                <div class="col-md-4">
                                                    <label class="form-label">Subject</label>
                                                    <div class="input-group">
                                                        <select name="subjects[]" class="form-select subject-select">
                                                            <option value="">Select subject</option>
                                                            @foreach($subjects as $subj)
                                                                <option value="{{ $subj->id }}" {{ $subj->id == $subjectId ? 'selected' : '' }}>{{ $subj->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="button" class="btn btn-outline-secondary btn-sm add-new-subject-btn" data-bs-toggle="modal" data-bs-target="#addSubjectModal" tabindex="-1" title="Add New Subject">
                                                            <i class="bi bi-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Rate per hour (₹)</label>
                                                    <div class="input-group">
                                                        <span class="input-group-text">₹</span>
                                                        <input type="number" name="subject_rates[]" min="0" step="0.01" class="form-control" placeholder="500">
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <label class="form-label">Proficiency Level</label>
                                                    <select name="subject_proficiency[]" class="form-select">
                                                        <option value="beginner">Beginner</option>
                                                        <option value="intermediate" selected>Intermediate</option>
                                                        <option value="advanced">Advanced</option>
                                                        <option value="expert">Expert</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12 text-center text-muted py-4">No subjects available</div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Institute Information Section -->
                <div class="card mb-4">
                    <div class="card-header bg-secondary text-white d-flex align-items-center">
                        <i class="bi bi-building me-2"></i> Institute Information
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="institute_id" class="form-label">Associated Institute</label>
                                <select name="institute_id" id="institute_id" class="form-select">
                                    <option value="">Select institute (optional)</option>
                                    @foreach($institutes as $institute)
                                        <option value="{{ $institute->id }}" {{ old('institute_id', $teacherProfile->institute_id) == $institute->id ? 'selected' : '' }}>{{ $institute->institute_name }}</option>
                                    @endforeach
                                </select>
                                @error('institute_id')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="institute_experience" class="form-label">Institute Experience</label>
                                <textarea name="institute_experience" id="institute_experience" rows="2" class="form-control" placeholder="Describe your experience working with institutes...">{{ old('institute_experience', $teacherProfile->institute_experience) }}</textarea>
                                @error('institute_experience')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Section -->
                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('teacher.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-check-circle me-1"></i> Update Profile
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addSubjectModalLabel">Add New Subject</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addSubjectForm">
          <div class="mb-3">
            <label for="newSubjectName" class="form-label">Subject Name</label>
            <input type="text" class="form-control" id="newSubjectName" name="name" required>
            <div class="invalid-feedback">Please enter a subject name.</div>
          </div>
          <div id="addSubjectFeedback" class="mb-2"></div>
          <button type="submit" class="btn btn-primary">Add Subject</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Add Exam Package Modal -->
<div class="modal fade" id="addPackageModal" tabindex="-1" aria-labelledby="addPackageModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addPackageModalLabel">Add New Exam Preparation Package</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="addPackageForm">
          <div class="mb-3">
            <label for="newPackageName" class="form-label">Package Name</label>
            <input type="text" class="form-control" id="newPackageName" name="name" required>
            <div class="invalid-feedback">Please enter a package name.</div>
          </div>
          <div id="addPackageFeedback" class="mb-2"></div>
          <button type="submit" class="btn btn-primary">Add Package</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const addSubjectBtn = document.getElementById('add-subject');
    const subjectsContainer = document.getElementById('subjects-container');
    let currentSelectToUpdate = null;
    let currentPackageSelectToUpdate = null;

    // Add subject card
    addSubjectBtn.addEventListener('click', function() {
        const subjectItem = document.createElement('div');
        subjectItem.className = 'col-12 subject-item';
        subjectItem.innerHTML = `
            <div class="card position-relative mb-2">
                <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 end-0 m-2 remove-subject" title="Remove Subject">
                    <i class="bi bi-trash"></i>
                </button>
                <div class="card-body row g-3 align-items-end">
                    <div class="col-md-4">
                        <label class="form-label">Subject</label>
                        <div class="input-group">
                            <select name="subjects[]" class="form-select subject-select">
                                <option value="">Select subject</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="btn btn-outline-secondary btn-sm add-new-subject-btn" data-bs-toggle="modal" data-bs-target="#addSubjectModal" tabindex="-1" title="Add New Subject">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Rate per hour (₹)</label>
                        <div class="input-group">
                            <span class="input-group-text">₹</span>
                            <input type="number" name="subject_rates[]" min="0" step="0.01" class="form-control" placeholder="500">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Proficiency Level</label>
                        <select name="subject_proficiency[]" class="form-select">
                            <option value="beginner">Beginner</option>
                            <option value="intermediate" selected>Intermediate</option>
                            <option value="advanced">Advanced</option>
                            <option value="expert">Expert</option>
                        </select>
                    </div>
                </div>
            </div>
        `;
        subjectsContainer.appendChild(subjectItem);
        subjectItem.querySelector('.remove-subject').addEventListener('click', function() {
            subjectItem.remove();
        });
        // Add new subject button event
        subjectItem.querySelector('.add-new-subject-btn').addEventListener('click', function(e) {
            currentSelectToUpdate = subjectItem.querySelector('.subject-select');
            document.getElementById('newSubjectName').value = '';
            document.getElementById('addSubjectFeedback').innerHTML = '';
        });
    });

    // Remove subject for existing items
    document.querySelectorAll('.remove-subject').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.subject-item').remove();
        });
    });
    // Add new subject button for existing items
    document.querySelectorAll('.add-new-subject-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            currentSelectToUpdate = this.closest('.input-group').querySelector('.subject-select');
            document.getElementById('newSubjectName').value = '';
            document.getElementById('addSubjectFeedback').innerHTML = '';
        });
    });

    // Handle add subject modal form
    document.getElementById('addSubjectForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('newSubjectName').value.trim();
        const feedback = document.getElementById('addSubjectFeedback');
        if (!name) {
            feedback.innerHTML = '<div class="alert alert-danger">Please enter a subject name.</div>';
            return;
        }
        feedback.innerHTML = '<div class="text-info">Adding...</div>';
        fetch('/teacher/subjects/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.id && data.name) {
                // Add to all selects
                document.querySelectorAll('.subject-select').forEach(sel => {
                    const opt = document.createElement('option');
                    opt.value = data.id;
                    opt.textContent = data.name;
                    sel.appendChild(opt);
                });
                // Select in current
                if (currentSelectToUpdate) {
                    currentSelectToUpdate.value = data.id;
                }
                feedback.innerHTML = '<div class="alert alert-success">Subject added!</div>';
                setTimeout(() => {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addSubjectModal'));
                    modal.hide();
                }, 800);
            } else {
                feedback.innerHTML = '<div class="alert alert-danger">Could not add subject. Try again.</div>';
            }
        })
        .catch(() => {
            feedback.innerHTML = '<div class="alert alert-danger">Could not add subject. Try again.</div>';
        });
    });

    // Handle add package modal form
    document.getElementById('addPackageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const name = document.getElementById('newPackageName').value.trim();
        const feedback = document.getElementById('addPackageFeedback');
        if (!name) {
            feedback.innerHTML = '<div class="alert alert-danger">Please enter a package name.</div>';
            return;
        }
        feedback.innerHTML = '<div class="text-info">Adding...</div>';
        fetch('/teacher/exam-packages/add', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
            },
            body: JSON.stringify({ name })
        })
        .then(res => res.json())
        .then(data => {
            if (data && data.id && data.name) {
                // Add to all selects
                document.querySelectorAll('.exam-package-select').forEach(sel => {
                    const opt = document.createElement('option');
                    opt.value = data.id;
                    opt.textContent = data.name;
                    sel.appendChild(opt);
                });
                // Select in current (for multi-select, add to selected)
                document.querySelectorAll('.exam-package-select').forEach(sel => {
                    if (sel.multiple) {
                        sel.options[sel.options.length - 1].selected = true;
                    }
                });
                feedback.innerHTML = '<div class="alert alert-success">Package added!</div>';
                setTimeout(() => {
                    var modal = bootstrap.Modal.getInstance(document.getElementById('addPackageModal'));
                    modal.hide();
                }, 800);
            } else {
                feedback.innerHTML = '<div class="alert alert-danger">Could not add package. Try again.</div>';
            }
        })
        .catch(() => {
            feedback.innerHTML = '<div class="alert alert-danger">Could not add package. Try again.</div>';
        });
    });
});
</script>
@endsection 