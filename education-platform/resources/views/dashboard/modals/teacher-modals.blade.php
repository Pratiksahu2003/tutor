<!-- Teacher Modals -->

<!-- Add Subject Modal -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">
                    <i class="bi bi-plus-circle me-2"></i>Add New Subject
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addSubjectForm" action="{{ route('teacher.subjects.store') }}" method="POST">
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
                    </div>
                    <div class="row">
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
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="hourly_rate" class="form-label">Hourly Rate (₹) *</label>
                                <input type="number" class="form-control" id="hourly_rate" name="hourly_rate" min="100" required>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="subject_description" class="form-label">Description</label>
                        <textarea class="form-control" id="subject_description" name="description" rows="3" placeholder="Describe what you'll teach in this subject..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="subject_specializations" class="form-label">Specializations</label>
                        <input type="text" class="form-control" id="subject_specializations" name="specializations" placeholder="e.g., Algebra, Calculus, Trigonometry">
                        <small class="text-muted">Separate multiple specializations with commas</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-1"></i>Add Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Schedule Session Modal -->
<div class="modal fade" id="scheduleSessionModal" tabindex="-1" aria-labelledby="scheduleSessionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="scheduleSessionModalLabel">
                    <i class="bi bi-calendar-plus me-2"></i>Schedule New Session
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="scheduleSessionForm" action="{{ route('teacher.sessions.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_subject" class="form-label">Subject *</label>
                                <select class="form-select" id="session_subject" name="subject_id" required>
                                    <option value="">Select Subject</option>
                                    @if(isset($profile_data['subjects']))
                                        @foreach($profile_data['subjects'] as $subject)
                                            <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_student" class="form-label">Student</label>
                                <select class="form-select" id="session_student" name="student_id">
                                    <option value="">Select Student (Optional)</option>
                                    <!-- Students will be loaded dynamically -->
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_date" class="form-label">Date *</label>
                                <input type="date" class="form-control" id="session_date" name="date" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_time" class="form-label">Time *</label>
                                <input type="time" class="form-control" id="session_time" name="time" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_duration" class="form-label">Duration (minutes) *</label>
                                <select class="form-select" id="session_duration" name="duration" required>
                                    <option value="30">30 minutes</option>
                                    <option value="60" selected>1 hour</option>
                                    <option value="90">1.5 hours</option>
                                    <option value="120">2 hours</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="session_type" class="form-label">Session Type *</label>
                                <select class="form-select" id="session_type" name="type" required>
                                    <option value="online">Online</option>
                                    <option value="offline">Offline</option>
                                    <option value="hybrid">Hybrid</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="session_notes" class="form-label">Notes</label>
                        <textarea class="form-control" id="session_notes" name="notes" rows="3" placeholder="Any special instructions or topics to cover..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-1"></i>Schedule Session
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Earnings Report Modal -->
<div class="modal fade" id="earningsReportModal" tabindex="-1" aria-labelledby="earningsReportModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="earningsReportModalLabel">
                    <i class="bi bi-graph-up me-2"></i>Earnings Report
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="card-title">This Month</h6>
                                <div class="h4 text-primary">₹{{ number_format($stats['earnings_this_month'] ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="card-title">Total Earnings</h6>
                                <div class="h4 text-success">₹{{ number_format($stats['total_earnings'] ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="card-title">Sessions This Month</h6>
                                <div class="h4 text-info">{{ number_format($stats['sessions_this_month'] ?? 0) }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h6 class="card-title">Average per Session</h6>
                                <div class="h4 text-warning">₹{{ number_format(($stats['total_earnings'] ?? 0) / max($stats['total_sessions'] ?? 1, 1)) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-8">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Earnings Trend</h6>
                                <div class="chart-container">
                                    <canvas id="earningsTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Earnings by Subject</h6>
                                <div class="chart-container">
                                    <canvas id="earningsBySubjectChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-body">
                                <h6 class="card-title">Recent Transactions</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <th>Student</th>
                                                <th>Subject</th>
                                                <th>Duration</th>
                                                <th>Amount</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Recent transactions will be loaded here -->
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">
                    <i class="bi bi-download me-1"></i>Export Report
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Add Student Modal -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addStudentModalLabel">
                    <i class="bi bi-person-plus me-2"></i>Add New Student
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addStudentForm" action="{{ route('teacher.students.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_name" class="form-label">Student Name *</label>
                                <input type="text" class="form-control" id="student_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="student_email" name="email" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_phone" class="form-label">Phone</label>
                                <input type="tel" class="form-control" id="student_phone" name="phone">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="student_age" name="age" min="5" max="100">
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="student_subjects" class="form-label">Subjects of Interest</label>
                        <select class="form-select" id="student_subjects" name="subjects[]" multiple>
                            @if(isset($profile_data['subjects']))
                                @foreach($profile_data['subjects'] as $subject)
                                    <option value="{{ $subject->id }}">{{ $subject->name }}</option>
                                @endforeach
                            @endif
                        </select>
                        <small class="text-muted">Hold Ctrl/Cmd to select multiple subjects</small>
                    </div>
                    <div class="mb-3">
                        <label for="student_goals" class="form-label">Learning Goals</label>
                        <textarea class="form-control" id="student_goals" name="goals" rows="3" placeholder="What does the student want to achieve?"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-person-plus me-1"></i>Add Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div> 