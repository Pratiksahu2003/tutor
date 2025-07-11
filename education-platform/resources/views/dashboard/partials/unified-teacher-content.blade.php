<!-- Teacher Dashboard Content -->
<div class="row">
    <!-- Teaching Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-book me-2"></i>Subject Management</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                    <i class="bi bi-plus-circle me-1"></i>Add Subject
                </button>
            </div>
            
            @if(isset($profile_data['subjects']) && $profile_data['subjects']->count() > 0)
                <div class="row">
                    @foreach($profile_data['subjects'] as $subject)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="card-title mb-1">{{ $subject->name }}</h6>
                                            <small class="text-muted">{{ $subject->category }}</small>
                                        </div>
                                        <span class="badge bg-primary">{{ $subject->level }}</span>
                                    </div>
                                    <div class="mt-3">
                                        <small class="text-muted">
                                            <i class="bi bi-people me-1"></i>{{ $subject->students_count ?? 0 }} students
                                        </small>
                                        <br>
                                        <small class="text-muted">
                                            <i class="bi bi-currency-rupee me-1"></i>₹{{ $subject->hourly_rate ?? 0 }}/hour
                                        </small>
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
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                        <i class="bi bi-plus-circle me-1"></i>Add Your First Subject
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Earnings & Reports Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-graph-up me-2"></i>Earnings & Reports</h5>
                <div class="period-toggle">
                    <button class="btn active" data-period="week">Week</button>
                    <button class="btn" data-period="month">Month</button>
                    <button class="btn" data-period="year">Year</button>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-8">
                    <div class="chart-container">
                        <canvas id="earningsChart"></canvas>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Earnings Summary</h6>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>This Month</span>
                                    <strong>₹{{ number_format($stats['earnings_this_month']) }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Total Earnings</span>
                                    <strong>₹{{ number_format($stats['total_earnings']) }}</strong>
                                </div>
                            </div>
                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span>Average per Session</span>
                                    <strong>₹{{ number_format($stats['total_earnings'] / max($stats['total_sessions'], 1)) }}</strong>
                                </div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm w-100" data-bs-toggle="modal" data-bs-target="#earningsReportModal">
                                <i class="bi bi-download me-1"></i>Download Report
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Student Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-people me-2"></i>Student Management</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
                    <i class="bi bi-person-plus me-1"></i>Add Student
                </button>
            </div>
            
            @if(isset($teaching_data['student_inquiries']) && $teaching_data['student_inquiries']->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Student</th>
                                <th>Subject</th>
                                <th>Inquiry Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teaching_data['student_inquiries'] as $inquiry)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $inquiry->student->profile_image ? asset('storage/' . $inquiry->student->profile_image) : asset('images/default-avatar.png') }}" 
                                                 alt="Student" class="rounded-circle me-2" width="32" height="32">
                                            <div>
                                                <div class="fw-bold">{{ $inquiry->student->name }}</div>
                                                <small class="text-muted">{{ $inquiry->student->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $inquiry->subject->name }}</td>
                                    <td>{{ $inquiry->created_at->format('M d, Y') }}</td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary">Accept</button>
                                        <button class="btn btn-sm btn-outline-secondary">View</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-people text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No student inquiries at the moment.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Schedule Management Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5><i class="bi bi-calendar me-2"></i>Schedule Management</h5>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#scheduleSessionModal">
                    <i class="bi bi-plus-circle me-1"></i>Schedule Session
                </button>
            </div>
            
            @if(isset($teaching_data['upcoming_sessions']) && $teaching_data['upcoming_sessions']->count() > 0)
                <div class="row">
                    @foreach($teaching_data['upcoming_sessions'] as $session)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">{{ $session->subject->name }}</h6>
                                        <span class="badge bg-primary">{{ $session->status }}</span>
                                    </div>
                                    <p class="card-text">
                                        <i class="bi bi-person me-1"></i>{{ $session->student->name }}
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <small class="text-muted">
                                            <i class="bi bi-clock me-1"></i>{{ $session->scheduled_at->format('M d, h:i A') }}
                                        </small>
                                        <small class="text-success">
                                            <i class="bi bi-currency-rupee me-1"></i>₹{{ $session->amount }}
                                        </small>
                                    </div>
                                    <div class="mt-3">
                                        <button class="btn btn-sm btn-outline-primary">Join Session</button>
                                        <button class="btn btn-sm btn-outline-secondary">Reschedule</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-calendar text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2">No upcoming sessions scheduled.</p>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#scheduleSessionModal">
                        <i class="bi bi-plus-circle me-1"></i>Schedule Your First Session
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Performance Analytics Section -->
    <div class="col-12 mb-4">
        <div class="management-section">
            <h5><i class="bi bi-bar-chart me-2"></i>Performance Analytics</h5>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Session Completion Rate</h6>
                            <div class="progress mb-3" style="height: 10px;">
                                <div class="progress-bar bg-success" style="width: {{ ($stats['completed_sessions'] / max($stats['total_sessions'], 1)) * 100 }}%"></div>
                            </div>
                            <small class="text-muted">{{ $stats['completed_sessions'] }} of {{ $stats['total_sessions'] }} sessions completed</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">Student Satisfaction</h6>
                            <div class="d-flex align-items-center">
                                <div class="me-3">
                                    <span class="h4 mb-0">{{ number_format($stats['average_rating'], 1) }}</span>
                                    <small class="text-muted">/ 5.0</small>
                                </div>
                                <div>
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @endfor
                                </div>
                            </div>
                            <small class="text-muted">Based on {{ $stats['completed_sessions'] }} reviews</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 