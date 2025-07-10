<!-- Recent Learning Activity -->
<div class="card mb-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-clock-history me-2"></i>Recent Learning Activity
        </h5>
        <a href="#" class="btn btn-sm btn-outline-primary">View All</a>
    </div>
    <div class="card-body">
        <div class="activity-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">Mathematics Session with John Doe</h6>
                    <p class="text-muted mb-1">Completed: Algebra Basics</p>
                    <small class="text-success">
                        <i class="bi bi-check-circle me-1"></i>Completed
                    </small>
                </div>
                <small class="text-muted">2 hours ago</small>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">Physics Session with Sarah Wilson</h6>
                    <p class="text-muted mb-1">Topic: Newton's Laws</p>
                    <small class="text-warning">
                        <i class="bi bi-clock me-1"></i>In Progress
                    </small>
                </div>
                <small class="text-muted">1 day ago</small>
            </div>
        </div>
        
        <div class="activity-item">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <h6 class="mb-1">English Session with Mike Johnson</h6>
                    <p class="text-muted mb-1">Essay Writing Techniques</p>
                    <small class="text-info">
                        <i class="bi bi-calendar me-1"></i>Scheduled
                    </small>
                </div>
                <small class="text-muted">Tomorrow</small>
            </div>
        </div>
    </div>
</div>

<!-- Learning Progress -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-graph-up me-2"></i>Learning Progress
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Mathematics</span>
                    <span class="text-primary fw-bold">85%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-primary" style="width: 85%"></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Physics</span>
                    <span class="text-success fw-bold">72%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-success" style="width: 72%"></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>English</span>
                    <span class="text-info fw-bold">90%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-info" style="width: 90%"></div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span>Chemistry</span>
                    <span class="text-warning fw-bold">65%</span>
                </div>
                <div class="progress" style="height: 8px;">
                    <div class="progress-bar bg-warning" style="width: 65%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recommended Teachers -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-person-plus me-2"></i>Recommended Teachers
        </h5>
        <a href="{{ route('search.teachers') }}" class="btn btn-sm btn-outline-primary">Find More</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card border">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://via.placeholder.com/40" alt="Teacher" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <h6 class="mb-0">Dr. Emily Chen</h6>
                                <small class="text-muted">Mathematics Expert</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i> 4.9
                                </span>
                                <small class="text-muted">(127 reviews)</small>
                            </div>
                            <span class="text-success fw-bold">$25/hr</span>
                        </div>
                        <button class="btn btn-primary btn-sm w-100 mt-2" onclick="quickAction('find-teachers')">
                            View Profile
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card border">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-2">
                            <img src="https://via.placeholder.com/40" alt="Teacher" class="rounded-circle me-2" width="40" height="40">
                            <div>
                                <h6 class="mb-0">Prof. David Lee</h6>
                                <small class="text-muted">Physics Specialist</small>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-warning">
                                    <i class="bi bi-star-fill"></i> 4.8
                                </span>
                                <small class="text-muted">(89 reviews)</small>
                            </div>
                            <span class="text-success fw-bold">$30/hr</span>
                        </div>
                        <button class="btn btn-primary btn-sm w-100 mt-2" onclick="quickAction('find-teachers')">
                            View Profile
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 