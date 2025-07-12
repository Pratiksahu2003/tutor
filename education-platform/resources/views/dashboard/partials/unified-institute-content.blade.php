<!-- Institute Dashboard Content -->
<div class="row">
    <!-- Branch Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-building me-2"></i>Branch Management</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                    <i class="bi bi-plus-circle me-1"></i>Add Branch
                </button>
            </div>
            
            @if(isset($institute_data['branches']) && $institute_data['branches']->count() > 0)
                <div class="row">
                    @foreach($institute_data['branches'] as $branch)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $branch->name }}</h6>
                                            <small class="text-muted">{{ $branch->address }}</small>
                                        </div>
                                        <span class="badge bg-success">Active</span>
                                    </div>
                                    <div class="mt-3">
                                        <div class="row text-center">
                                            <div class="col-4">
                                                <div class="fw-bold">{{ $branch->teachers_count ?? 0 }}</div>
                                                <small class="text-muted">Teachers</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold">{{ $branch->students_count ?? 0 }}</div>
                                                <small class="text-muted">Students</small>
                                            </div>
                                            <div class="col-4">
                                                <div class="fw-bold">{{ $branch->sessions_count ?? 0 }}</div>
                                                <small class="text-muted">Sessions</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Manage</button>
                                        <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-building text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No branches added yet. Start by adding your first branch!</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBranchModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Your First Branch
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Teacher Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-person-badge me-2"></i>Teacher Management</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                    <i class="bi bi-person-plus me-1"></i>Add Teacher
                </button>
            </div>
            
            @if(isset($institute_data['teachers']) && $institute_data['teachers']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Teacher</th>
                                <th>Subjects</th>
                                <th>Students</th>
                                <th>Rating</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($institute_data['teachers'] as $teacher)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $teacher->user->profile_image ? asset('storage/' . $teacher->user->profile_image) : asset('images/default-avatar.png') }}" 
                                                 alt="Teacher" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-bold">{{ $teacher->user->name }}</div>
                                                <small class="text-muted">{{ $teacher->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        @foreach($teacher->subjects->take(3) as $subject)
                                            <span class="badge bg-light text-dark me-1">{{ $subject->name }}</span>
                                        @endforeach
                                        @if($teacher->subjects->count() > 3)
                                            <span class="badge bg-secondary">+{{ $teacher->subjects->count() - 3 }} more</span>
                                        @endif
                                    </td>
                                    <td>{{ $teacher->students_count ?? 0 }}</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-1">{{ number_format($teacher->average_rating ?? 0, 1) }}</span>
                                            <i class="bi bi-star-fill text-warning"></i>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary">View</button>
                                        <button class="btn btn-sm btn-outline-secondary">Edit</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-person-badge text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No teachers added yet. Start by adding your first teacher!</p>
                    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addTeacherModal">
                        <i class="bi bi-person-plus me-1"></i>Add Your First Teacher
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Subject Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-book me-2"></i>Subject Management</h5>
                <button class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="bi bi-plus-circle me-1"></i>Add Subject
                </button>
            </div>
            
            @if(isset($institute_data['subjects']) && $institute_data['subjects']->count() > 0)
                <div class="row">
                    @foreach($institute_data['subjects'] as $subject)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $subject->name }}</h6>
                                            <small class="text-muted">{{ $subject->category }}</small>
                                        </div>
                                        <span class="badge bg-info">{{ $subject->level }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="fw-bold">{{ $subject->teachers_count ?? 0 }}</div>
                                                <small class="text-muted">Teachers</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="fw-bold">{{ $subject->students_count ?? 0 }}</div>
                                                <small class="text-muted">Students</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Manage</button>
                                        <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-book text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No subjects added yet. Start by adding your first subject!</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Your First Subject
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Exam Type Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-file-text me-2"></i>Exam Type Management</h5>
                <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#addExamTypeModal">
                    <i class="bi bi-plus-circle me-1"></i>Add Exam Type
                </button>
            </div>
            
            @if(isset($institute_data['exam_types']) && $institute_data['exam_types']->count() > 0)
                <div class="row">
                    @foreach($institute_data['exam_types'] as $examType)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $examType->name }}</h6>
                                            <small class="text-muted">{{ $examType->description }}</small>
                                        </div>
                                        <span class="badge bg-warning">{{ $examType->duration }} min</span>
                                    </div>
                                    <div class="mt-3">
                                        <div class="row text-center">
                                            <div class="col-6">
                                                <div class="fw-bold">{{ $examType->questions_count ?? 0 }}</div>
                                                <small class="text-muted">Questions</small>
                                            </div>
                                            <div class="col-6">
                                                <div class="fw-bold">{{ $examType->passing_score ?? 0 }}%</div>
                                                <small class="text-muted">Passing Score</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Manage</button>
                                        <button class="btn btn-sm btn-outline-secondary">View Details</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-file-text text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No exam types added yet. Start by adding your first exam type!</p>
                    <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#addExamTypeModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Your First Exam Type
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Revenue & Analytics Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-graph-up me-2"></i>Revenue & Analytics</h5>
                <div class="period-toggle">
                    <button class="btn active" data-period="week">Week</button>
                    <button class="btn" data-period="month">Month</button>
                    <button class="btn" data-period="year">Year</button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-container">
                        <canvas id="revenueChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Revenue Summary</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>This Month</span>
                                    <strong>₹{{ number_format($stats['monthly_revenue'] ?? 0) }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total Revenue</span>
                                    <strong>₹{{ number_format($stats['total_revenue'] ?? 0) }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Average per Session</span>
                                    <strong>₹{{ number_format(($stats['total_revenue'] ?? 0) / max($stats['total_sessions'] ?? 1, 1)) }}</strong>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100">
                                <i class="bi bi-download me-1"></i>Download Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Reports Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <h5><i class="bi bi-bar-chart me-2"></i>Performance Reports</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Branch Performance</h6>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="branchPerformanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Teacher Performance</h6>
                            <div class="chart-container" style="height: 200px;">
                                <canvas id="teacherPerformanceChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 