<!-- Institute Modals -->

<!-- Add Branch Modal -->
<div class="modal fade" id="addBranchModal" tabindex="-1" aria-labelledby="addBranchModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addBranchModalLabel">
                    <i class="bi bi-building me-2"></i>Add New Branch
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addBranchForm" action="{{ route('institute.branches.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_name" class="form-label">Branch Name *</label>
                                <input type="text" class="form-control" id="branch_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_code" class="form-label">Branch Code *</label>
                                <input type="text" class="form-control" id="branch_code" name="code" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="branch_address" class="form-label">Address *</label>
                        <textarea class="form-control" id="branch_address" name="address" rows="3" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_city" class="form-label">City *</label>
                                <input type="text" class="form-control" id="branch_city" name="city" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_state" class="form-label">State *</label>
                                <input type="text" class="form-control" id="branch_state" name="state" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_pincode" class="form-label">Pincode *</label>
                                <input type="text" class="form-control" id="branch_pincode" name="pincode" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="branch_phone" class="form-label">Phone *</label>
                                <input type="tel" class="form-control" id="branch_phone" name="phone" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="branch_email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="branch_email" name="email">
                    </div>
                    <div class="mb-3">
                        <label for="branch_manager" class="form-label">Branch Manager</label>
                        <input type="text" class="form-control" id="branch_manager" name="manager">
                    </div>
                    <div class="mb-3">
                        <label for="branch_capacity" class="form-label">Student Capacity</label>
                        <input type="number" class="form-control" id="branch_capacity" name="capacity" min="10">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-building me-1"></i>Add Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Teacher Modal -->
<div class="modal fade" id="addTeacherModal" tabindex="-1" aria-labelledby="addTeacherModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addTeacherModalLabel">
                    <i class="bi bi-person-badge me-2"></i>Add New Teacher
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addTeacherForm" action="{{ route('institute.teachers.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="teacher_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="teacher_email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_phone" class="form-label">Phone *</label>
                                <input type="tel" class="form-control" id="teacher_phone" name="phone" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_experience" class="form-label">Experience (Years)</label>
                                <input type="number" class="form-control" id="teacher_experience" name="experience_years" min="0" max="50">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_subjects" class="form-label">Subjects *</label>
                        <select class="form-select" id="teacher_subjects" name="subjects[]" multiple required>
                            @if(isset($institute_data['subjects']))
                                @foreach($institute_data['subjects'] as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple subjects</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_qualification" class="form-label">Qualification</label>
                                <input type="text" class="form-control" id="teacher_qualification" name="qualification">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="teacher_hourly_rate" class="form-label">Hourly Rate (₹)</label>
                                <input type="number" class="form-control" id="teacher_hourly_rate" name="hourly_rate" min="100">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_bio" class="form-label">Bio</label>
                        <textarea class="form-control" id="teacher_bio" name="bio" rows="3" placeholder="Brief description about the teacher..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="teacher_branches" class="form-label">Assigned Branches</label>
                        <select class="form-select" id="teacher_branches" name="branches[]" multiple>
                            @if(isset($institute_data['branches']))
                                @foreach($institute_data['branches'] as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-badge me-1"></i>Add Teacher
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">
                    <i class="bi bi-book me-2"></i>Add New Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSubjectForm" action="{{ route('institute.subjects.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_name" class="form-label">Subject Name *</label>
                                <input type="text" class="form-control" id="subject_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_code" class="form-label">Subject Code *</label>
                                <input type="text" class="form-control" id="subject_code" name="code" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_category" class="form-label">Category *</label>
                                <select class="form-select" id="subject_category" name="category" required>
                                    <option value="">Select Category</option>
                                    <option value="academic">Academic</option>
                                    <option value="competitive">Competitive</option>
                                    <option value="language">Language</option>
                                    <option value="technical">Technical</option>
                                    <option value="arts">Arts & Creative</option>
                                    <option value="sports">Sports & Fitness</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_level" class="form-label">Level *</label>
                                <select class="form-select" id="subject_level" name="level" required>
                                    <option value="">Select Level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="expert">Expert</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject_description" class="form-label">Description</label>
                        <textarea class="form-control" id="subject_description" name="description" rows="3" placeholder="Describe the subject curriculum..."></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_duration" class="form-label">Course Duration (Hours)</label>
                                <input type="number" class="form-control" id="subject_duration" name="duration" min="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="subject_fee" class="form-label">Course Fee (₹)</label>
                                <input type="number" class="form-control" id="subject_fee" name="fee" min="0">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject_branches" class="form-label">Available at Branches</label>
                        <select class="form-select" id="subject_branches" name="branches[]" multiple>
                            @if(isset($institute_data['branches']))
                                @foreach($institute_data['branches'] as $branch)
                                    <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-book me-1"></i>Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Exam Type Modal -->
<div class="modal fade" id="addExamTypeModal" tabindex="-1" aria-labelledby="addExamTypeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addExamTypeModalLabel">
                    <i class="bi bi-file-text me-2"></i>Add New Exam Type
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addExamTypeForm" action="{{ route('institute.exam-types.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exam_name" class="form-label">Exam Name *</label>
                                <input type="text" class="form-control" id="exam_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exam_code" class="form-label">Exam Code *</label>
                                <input type="text" class="form-control" id="exam_code" name="code" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exam_duration" class="form-label">Duration (Minutes) *</label>
                                <input type="number" class="form-control" id="exam_duration" name="duration" min="15" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exam_questions" class="form-label">Total Questions</label>
                                <input type="number" class="form-control" id="exam_questions" name="questions_count" min="1">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exam_passing_score" class="form-label">Passing Score (%)</label>
                                <input type="number" class="form-control" id="exam_passing_score" name="passing_score" min="0" max="100">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="exam_max_attempts" class="form-label">Max Attempts</label>
                                <input type="number" class="form-control" id="exam_max_attempts" name="max_attempts" min="1" value="3">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="exam_description" class="form-label">Description</label>
                        <textarea class="form-control" id="exam_description" name="description" rows="3" placeholder="Describe the exam format and requirements..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="exam_subjects" class="form-label">Applicable Subjects</label>
                        <select class="form-select" id="exam_subjects" name="subjects[]" multiple>
                            @if(isset($institute_data['subjects']))
                                @foreach($institute_data['subjects'] as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="exam_randomize" name="randomize_questions" checked>
                            <label class="form-check-label" for="exam_randomize">
                                Randomize Questions
                            </label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="bi bi-file-text me-1"></i>Add Exam Type
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 