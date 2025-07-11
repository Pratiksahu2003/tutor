/**
 * Unified Dashboard JavaScript
 * Comprehensive functionality for all user roles with real-time updates
 */

class UnifiedDashboardManager {
    constructor() {
        this.currentUser = null;
        this.userRole = null;
        this.updateInterval = null;
        this.charts = {};
        this.modals = {};
        this.init();
    }

    init() {
        this.currentUser = this.getCurrentUser();
        this.userRole = this.currentUser?.role || 'student';
        this.setupEventListeners();
        this.initializeCharts();
        this.initializeModals();
        this.startAutoRefresh();
        this.setupResponsiveHandlers();
    }

    getCurrentUser() {
        const userElement = document.querySelector('meta[name="current-user"]');
        if (userElement) {
            return JSON.parse(userElement.getAttribute('content'));
        }
        return null;
    }

    setupEventListeners() {
        // Period toggle buttons
        document.querySelectorAll('[data-period]').forEach(button => {
            button.addEventListener('click', (e) => {
                this.handlePeriodToggle(e);
            });
        });

        // Quick action buttons
        document.querySelectorAll('.quick-action-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                this.handleQuickAction(e);
            });
        });

        // Modal form submissions
        this.setupModalFormSubmissions();

        // Real-time updates
        this.setupRealTimeUpdates();

        // Responsive handlers
        this.setupResponsiveHandlers();
    }

    setupModalFormSubmissions() {
        // Teacher modals
        if (this.userRole === 'teacher') {
            this.setupTeacherModals();
        }

        // Institute modals
        if (this.userRole === 'institute') {
            this.setupInstituteModals();
        }

        // Admin modals
        if (this.userRole === 'admin') {
            this.setupAdminModals();
        }
    }

    setupTeacherModals() {
        // Add Subject Modal
        const addSubjectForm = document.getElementById('addSubjectForm');
        if (addSubjectForm) {
            addSubjectForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddSubject(e);
            });
        }

        // Schedule Session Modal
        const scheduleSessionForm = document.getElementById('scheduleSessionForm');
        if (scheduleSessionForm) {
            scheduleSessionForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleScheduleSession(e);
            });
        }

        // Add Student Modal
        const addStudentForm = document.getElementById('addStudentForm');
        if (addStudentForm) {
            addStudentForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddStudent(e);
            });
        }
    }

    setupInstituteModals() {
        // Add Branch Modal
        const addBranchForm = document.getElementById('addBranchForm');
        if (addBranchForm) {
            addBranchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddBranch(e);
            });
        }

        // Add Teacher Modal
        const addTeacherForm = document.getElementById('addTeacherForm');
        if (addTeacherForm) {
            addTeacherForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddTeacher(e);
            });
        }

        // Add Subject Modal
        const addSubjectForm = document.getElementById('addSubjectForm');
        if (addSubjectForm) {
            addSubjectForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddSubject(e);
            });
        }

        // Add Exam Type Modal
        const addExamTypeForm = document.getElementById('addExamTypeForm');
        if (addExamTypeForm) {
            addExamTypeForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleAddExamType(e);
            });
        }
    }

    setupAdminModals() {
        // Admin-specific modal handlers
        const manageUsersForm = document.getElementById('manageUsersForm');
        if (manageUsersForm) {
            manageUsersForm.addEventListener('submit', (e) => {
                e.preventDefault();
                this.handleManageUsers(e);
            });
        }
    }

    handlePeriodToggle(event) {
        const button = event.currentTarget;
        const period = button.dataset.period;
        
        // Update active state
        button.parentElement.querySelectorAll('[data-period]').forEach(btn => {
            btn.classList.remove('active');
        });
        button.classList.add('active');

        // Update statistics based on period
        this.updateStatisticsByPeriod(period);
    }

    handleQuickAction(event) {
        const button = event.currentTarget;
        const action = button.dataset.action;
        
        switch (action) {
            case 'refresh':
                this.refreshDashboard();
                break;
            case 'export':
                this.exportData();
                break;
            case 'print':
                this.printDashboard();
                break;
            default:
                this.handleCustomAction(action);
        }
    }

    // Teacher-specific handlers
    handleAddSubject(event) {
        const form = event.target;
        const formData = new FormData(form);
        
        this.showLoadingState();
        
        fetch('/teacher/subjects', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Subject added successfully!', 'success');
                this.refreshDashboard();
                bootstrap.Modal.getInstance(document.getElementById('addSubjectModal')).hide();
            } else {
                this.showNotification(data.message || 'Failed to add subject', 'error');
            }
        })
        .catch(error => {
            console.error('Error adding subject:', error);
            this.showNotification('An error occurred while adding subject', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    handleScheduleSession(event) {
        const form = event.target;
        const formData = new FormData(form);
        
        this.showLoadingState();
        
        fetch('/teacher/sessions', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Session scheduled successfully!', 'success');
                this.refreshDashboard();
                bootstrap.Modal.getInstance(document.getElementById('scheduleSessionModal')).hide();
            } else {
                this.showNotification(data.message || 'Failed to schedule session', 'error');
            }
        })
        .catch(error => {
            console.error('Error scheduling session:', error);
            this.showNotification('An error occurred while scheduling session', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    handleAddStudent(event) {
        const form = event.target;
        const formData = new FormData(form);
        
        this.showLoadingState();
        
        fetch('/teacher/students', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Student added successfully!', 'success');
                this.refreshDashboard();
                bootstrap.Modal.getInstance(document.getElementById('addStudentModal')).hide();
            } else {
                this.showNotification(data.message || 'Failed to add student', 'error');
            }
        })
        .catch(error => {
            console.error('Error adding student:', error);
            this.showNotification('An error occurred while adding student', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    // Institute-specific handlers
    handleAddBranch(event) {
        const form = event.target;
        const formData = new FormData(form);
        
        this.showLoadingState();
        
        fetch('/institute/branches', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Branch added successfully!', 'success');
                this.refreshDashboard();
                bootstrap.Modal.getInstance(document.getElementById('addBranchModal')).hide();
            } else {
                this.showNotification(data.message || 'Failed to add branch', 'error');
            }
        })
        .catch(error => {
            console.error('Error adding branch:', error);
            this.showNotification('An error occurred while adding branch', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    handleAddTeacher(event) {
        const form = event.target;
        const formData = new FormData(form);
        
        this.showLoadingState();
        
        fetch('/institute/teachers', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Teacher added successfully!', 'success');
                this.refreshDashboard();
                bootstrap.Modal.getInstance(document.getElementById('addTeacherModal')).hide();
            } else {
                this.showNotification(data.message || 'Failed to add teacher', 'error');
            }
        })
        .catch(error => {
            console.error('Error adding teacher:', error);
            this.showNotification('An error occurred while adding teacher', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    handleAddExamType(event) {
        const form = event.target;
        const formData = new FormData(form);
        
        this.showLoadingState();
        
        fetch('/institute/exam-types', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.showNotification('Exam type added successfully!', 'success');
                this.refreshDashboard();
                bootstrap.Modal.getInstance(document.getElementById('addExamTypeModal')).hide();
            } else {
                this.showNotification(data.message || 'Failed to add exam type', 'error');
            }
        })
        .catch(error => {
            console.error('Error adding exam type:', error);
            this.showNotification('An error occurred while adding exam type', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    setupRealTimeUpdates() {
        // Set up WebSocket connection for real-time updates
        if (typeof WebSocket !== 'undefined') {
            this.setupWebSocket();
        } else {
            // Fallback to polling
            this.setupPolling();
        }
    }

    setupWebSocket() {
        const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:';
        const wsUrl = `${protocol}//${window.location.host}/ws/dashboard`;
        
        try {
            this.ws = new WebSocket(wsUrl);
            
            this.ws.onopen = () => {
                console.log('WebSocket connected');
                this.ws.send(JSON.stringify({
                    type: 'subscribe',
                    user_id: this.currentUser?.id,
                    role: this.userRole
                }));
            };

            this.ws.onmessage = (event) => {
                const data = JSON.parse(event.data);
                this.handleRealTimeUpdate(data);
            };

            this.ws.onclose = () => {
                console.log('WebSocket disconnected');
                this.setupPolling();
            };
        } catch (error) {
            console.error('WebSocket connection failed:', error);
            this.setupPolling();
        }
    }

    setupPolling() {
        // Poll for updates every 30 seconds
        this.updateInterval = setInterval(() => {
            this.pollForUpdates();
        }, 30000);
    }

    pollForUpdates() {
        const endpoint = this.getUpdateEndpoint();
        
        fetch(endpoint, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            this.handleRealTimeUpdate(data);
        })
        .catch(error => {
            console.error('Polling update failed:', error);
        });
    }

    getUpdateEndpoint() {
        switch (this.userRole) {
            case 'admin':
                return '/admin/api/dashboard-updates';
            case 'teacher':
                return '/teacher/api/dashboard-updates';
            case 'institute':
                return '/institute/api/dashboard-updates';
            case 'student':
                return '/student/api/dashboard-updates';
            default:
                return '/api/dashboard-updates';
        }
    }

    handleRealTimeUpdate(data) {
        if (data.type === 'stats_update') {
            this.updateStatistics(data.stats);
        } else if (data.type === 'notification') {
            this.showNotification(data.notification);
        } else if (data.type === 'activity') {
            this.addActivityItem(data.activity);
        }
    }

    updateStatisticsByPeriod(period) {
        const endpoint = this.getStatsEndpoint();
        
        fetch(`${endpoint}?period=${period}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            this.updateStatistics(data.stats);
            this.updateCharts(data.charts);
        })
        .catch(error => {
            console.error('Error updating statistics:', error);
        });
    }

    getStatsEndpoint() {
        switch (this.userRole) {
            case 'admin':
                return '/admin/api/stats';
            case 'teacher':
                return '/teacher/api/stats';
            case 'institute':
                return '/institute/api/stats';
            case 'student':
                return '/student/api/stats';
            default:
                return '/api/stats';
        }
    }

    updateStatistics(data) {
        // Update statistics cards
        Object.keys(data).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"]`);
            if (element) {
                element.textContent = this.formatNumber(data[key]);
            }
        });
    }

    updateCharts(data) {
        // Update charts with new data
        Object.keys(this.charts).forEach(chartId => {
            if (data[chartId]) {
                this.charts[chartId].data = data[chartId];
                this.charts[chartId].update();
            }
        });
    }

    initializeCharts() {
        // Initialize role-specific charts
        switch (this.userRole) {
            case 'teacher':
                this.initializeTeacherCharts();
                break;
            case 'institute':
                this.initializeInstituteCharts();
                break;
            case 'admin':
                this.initializeAdminCharts();
                break;
            case 'student':
                this.initializeStudentCharts();
                break;
        }
    }

    initializeTeacherCharts() {
        // Earnings Chart
        const earningsCtx = document.getElementById('earningsChart');
        if (earningsCtx) {
            this.charts.earningsChart = new Chart(earningsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Monthly Earnings',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        // Earnings by Subject Chart
        const earningsBySubjectCtx = document.getElementById('earningsBySubjectChart');
        if (earningsBySubjectCtx) {
            this.charts.earningsBySubjectChart = new Chart(earningsBySubjectCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Mathematics', 'Physics', 'Chemistry', 'English'],
                    datasets: [{
                        data: [30000, 25000, 20000, 15000],
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.8)',
                            'rgba(54, 162, 235, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(75, 192, 192, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    initializeInstituteCharts() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart');
        if (revenueCtx) {
            this.charts.revenueChart = new Chart(revenueCtx, {
                type: 'bar',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: [50000, 65000, 55000, 75000, 70000, 85000],
                        backgroundColor: 'rgba(54, 162, 235, 0.8)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                        }
                    }
                }
            });
        }

        // Branch Performance Chart
        const branchPerformanceCtx = document.getElementById('branchPerformanceChart');
        if (branchPerformanceCtx) {
            this.charts.branchPerformanceChart = new Chart(branchPerformanceCtx, {
                type: 'radar',
                data: {
                    labels: ['Students', 'Teachers', 'Sessions', 'Revenue', 'Rating'],
                    datasets: [{
                        label: 'Branch A',
                        data: [85, 90, 75, 80, 85],
                        borderColor: 'rgb(255, 99, 132)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)'
                    }, {
                        label: 'Branch B',
                        data: [70, 85, 80, 75, 90],
                        borderColor: 'rgb(54, 162, 235)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    initializeAdminCharts() {
        // Admin-specific charts
        const userGrowthCtx = document.getElementById('userGrowthChart');
        if (userGrowthCtx) {
            this.charts.userGrowthChart = new Chart(userGrowthCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Total Users',
                        data: [100, 150, 200, 250, 300, 350],
                        borderColor: 'rgb(75, 192, 192)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    initializeStudentCharts() {
        // Student-specific charts
        const progressCtx = document.getElementById('progressChart');
        if (progressCtx) {
            this.charts.progressChart = new Chart(progressCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Completed', 'In Progress', 'Not Started'],
                    datasets: [{
                        data: [70, 20, 10],
                        backgroundColor: [
                            'rgba(75, 192, 192, 0.8)',
                            'rgba(255, 205, 86, 0.8)',
                            'rgba(255, 99, 132, 0.8)'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }
    }

    initializeModals() {
        // Initialize Bootstrap modals
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            this.modals[modal.id] = new bootstrap.Modal(modal);
        });
    }

    setupResponsiveHandlers() {
        // Handle responsive behavior
        const handleResize = () => {
            // Update chart sizes on resize
            Object.keys(this.charts).forEach(chartId => {
                if (this.charts[chartId]) {
                    this.charts[chartId].resize();
                }
            });
        };

        window.addEventListener('resize', handleResize);
    }

    refreshDashboard() {
        this.showLoadingState();
        
        fetch(window.location.href, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            this.updateDashboardContent(html);
        })
        .catch(error => {
            console.error('Error refreshing dashboard:', error);
            this.showNotification('Failed to refresh dashboard', 'error');
        })
        .finally(() => {
            this.hideLoadingState();
        });
    }

    updateDashboardContent(html) {
        // Parse the HTML and update specific sections
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        
        // Update statistics cards
        const newStats = doc.querySelectorAll('.stats-widget');
        const currentStats = document.querySelectorAll('.stats-widget');
        
        newStats.forEach((newStat, index) => {
            if (currentStats[index]) {
                currentStats[index].innerHTML = newStat.innerHTML;
            }
        });
    }

    showLoadingState() {
        document.body.classList.add('loading');
    }

    hideLoadingState() {
        document.body.classList.remove('loading');
    }

    showNotification(message, type = 'info') {
        const alertClass = type === 'error' ? 'alert-danger' : 
                          type === 'success' ? 'alert-success' : 'alert-info';
        
        const alertHtml = `
            <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
                <i class="bi bi-${type === 'error' ? 'exclamation-triangle' : 
                                  type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        const container = document.querySelector('.container-fluid');
        if (container) {
            container.insertAdjacentHTML('afterbegin', alertHtml);
        }
    }

    formatNumber(num) {
        if (num >= 1000000) {
            return (num / 1000000).toFixed(1) + 'M';
        } else if (num >= 1000) {
            return (num / 1000).toFixed(1) + 'K';
        }
        return num.toString();
    }

    startAutoRefresh() {
        // Auto-refresh every 5 minutes
        setInterval(() => {
            this.pollForUpdates();
        }, 300000);
    }

    destroy() {
        // Cleanup
        if (this.updateInterval) {
            clearInterval(this.updateInterval);
        }
        
        if (this.ws) {
            this.ws.close();
        }
        
        Object.keys(this.charts).forEach(chartId => {
            if (this.charts[chartId]) {
                this.charts[chartId].destroy();
            }
        });
    }
}

// Initialize the dashboard when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    window.unifiedDashboard = new UnifiedDashboardManager();
});

// Cleanup on page unload
window.addEventListener('beforeunload', function() {
    if (window.unifiedDashboard) {
        window.unifiedDashboard.destroy();
    }
}); 