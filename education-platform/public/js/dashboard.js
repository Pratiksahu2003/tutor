/**
 * Admin Dashboard Charts and Analytics
 * Handles all dashboard-specific functionality including charts, statistics, and real-time updates
 */

class DashboardManager {
    constructor() {
        this.charts = {};
        this.refreshInterval = null;
        this.init();
    }

    init() {
        this.initCharts();
        this.initChartPeriodToggles();
        this.initRealTimeUpdates();
        this.initLeadManagement();
        this.bindEvents();
    }

    /**
     * Initialize all charts
     */
    initCharts() {
        this.initUserRegistrationChart();
        this.initUserDistributionChart();
        this.initLeadSourceChart();
        this.initRevenueChart();
    }

    /**
     * Initialize user registration trends chart
     */
    initUserRegistrationChart() {
        const ctx = document.getElementById('userRegistrationChart');
        if (!ctx) return;

        // Get data from window object or make AJAX call
        const chartData = window.chartData || this.getDefaultChartData();
        
        this.charts.userRegistration = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.registration?.labels || [],
                datasets: [{
                    label: 'Students',
                    data: chartData.registration?.students || [],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Teachers',
                    data: chartData.registration?.teachers || [],
                    borderColor: '#11998e',
                    backgroundColor: 'rgba(17, 153, 142, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Institutes',
                    data: chartData.registration?.institutes || [],
                    borderColor: '#f093fb',
                    backgroundColor: 'rgba(240, 147, 251, 0.1)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    /**
     * Initialize user distribution pie chart
     */
    initUserDistributionChart() {
        const ctx = document.getElementById('userDistributionChart');
        if (!ctx) return;

        const chartData = window.chartData || this.getDefaultChartData();
        
        this.charts.userDistribution = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Students', 'Teachers', 'Institutes', 'Admins'],
                datasets: [{
                    data: chartData.distribution || [50, 30, 15, 5],
                    backgroundColor: [
                        '#667eea',
                        '#11998e',
                        '#f093fb',
                        '#4facfe'
                    ],
                    borderWidth: 3,
                    borderColor: '#fff',
                    hoverBorderWidth: 5,
                    hoverBorderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '60%',
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            usePointStyle: true,
                            padding: 20
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = Math.round((context.parsed * 100) / total);
                                return `${context.label}: ${context.parsed} (${percentage}%)`;
                            }
                        }
                    }
                },
                animation: {
                    animateRotate: true,
                    duration: 1000
                }
            }
        });
    }

    /**
     * Initialize lead source chart
     */
    initLeadSourceChart() {
        const ctx = document.getElementById('leadSourceChart');
        if (!ctx) return;

        this.charts.leadSource = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Website', 'Google Ads', 'Facebook', 'Referral', 'Direct', 'Other'],
                datasets: [{
                    label: 'Leads',
                    data: [45, 35, 25, 20, 15, 10],
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(17, 153, 142, 0.8)',
                        'rgba(240, 147, 251, 0.8)',
                        'rgba(79, 172, 254, 0.8)',
                        'rgba(255, 193, 7, 0.8)',
                        'rgba(108, 117, 125, 0.8)'
                    ],
                    borderColor: [
                        '#667eea',
                        '#11998e',
                        '#f093fb',
                        '#4facfe',
                        '#ffc107',
                        '#6c757d'
                    ],
                    borderWidth: 2,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#667eea',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            precision: 0
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    /**
     * Initialize revenue chart
     */
    initRevenueChart() {
        const ctx = document.getElementById('revenueChart');
        if (!ctx) return;

        this.charts.revenue = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [12000, 15000, 18000, 22000, 25000, 28000],
                    borderColor: '#11998e',
                    backgroundColor: 'rgba(17, 153, 142, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#11998e',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 3,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#11998e',
                        borderWidth: 1,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return `Revenue: ₹${context.parsed.y.toLocaleString('en-IN')}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            precision: 0,
                            callback: function(value) {
                                return '₹' + value.toLocaleString('en-IN');
                            }
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        }
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });
    }

    /**
     * Initialize chart period toggles
     */
    initChartPeriodToggles() {
        const periodToggles = document.querySelectorAll('input[name="chartPeriod"]');
        
        periodToggles.forEach(toggle => {
            toggle.addEventListener('change', (e) => {
                if (e.target.checked) {
                    this.updateChartData(e.target.id);
                }
            });
        });
    }

    /**
     * Update chart data based on selected period
     */
    async updateChartData(period) {
        const loadingOverlay = AdminUtils.showLoading(
            document.querySelector('.card-body:has(#userRegistrationChart)')
        );

        try {
            const response = await AdminAjax.get(`/admin/api/analytics?period=${period}`);
            
            if (response.success && this.charts.userRegistration) {
                this.charts.userRegistration.data.labels = response.data.labels;
                this.charts.userRegistration.data.datasets[0].data = response.data.students;
                this.charts.userRegistration.data.datasets[1].data = response.data.teachers;
                this.charts.userRegistration.data.datasets[2].data = response.data.institutes;
                this.charts.userRegistration.update('active');
            }
        } catch (error) {
            console.error('Failed to update chart data:', error);
            AdminUtils.showToast('Failed to update chart data', 'error');
        } finally {
            AdminUtils.hideLoading(loadingOverlay);
        }
    }

    /**
     * Initialize real-time updates
     */
    initRealTimeUpdates() {
        // Update statistics every 30 seconds
        this.refreshInterval = setInterval(() => {
            this.updateStatistics();
        }, 30000);
    }

    /**
     * Update dashboard statistics
     */
    async updateStatistics() {
        try {
            const response = await AdminAjax.get('/admin/api/stats');
            
            if (response.success) {
                this.updateStatCards(response.data);
            }
        } catch (error) {
            console.error('Failed to update statistics:', error);
        }
    }

    /**
     * Update statistic cards
     */
    updateStatCards(data) {
        const statCards = {
            'total_users': data.total_users,
            'total_teachers': data.total_teachers,
            'total_institutes': data.total_institutes,
            'total_leads': data.total_leads
        };

        Object.entries(statCards).forEach(([key, value]) => {
            const element = document.querySelector(`[data-stat="${key}"] .stat-value`);
            if (element && element.textContent !== value.toString()) {
                this.animateNumber(element, parseInt(element.textContent) || 0, value);
            }
        });
    }

    /**
     * Animate number change
     */
    animateNumber(element, from, to, duration = 1000) {
        const startTime = performance.now();
        
        const animate = (currentTime) => {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);
            
            const current = Math.floor(from + (to - from) * progress);
            element.textContent = current.toLocaleString('en-IN');
            
            if (progress < 1) {
                requestAnimationFrame(animate);
            }
        };
        
        requestAnimationFrame(animate);
    }

    /**
     * Initialize lead management functions
     */
    initLeadManagement() {
        // Contact lead buttons
        const contactButtons = document.querySelectorAll('[data-action="contact-lead"]');
        contactButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                const leadId = e.target.getAttribute('data-lead-id');
                this.contactLead(leadId);
            });
        });

        // Lead status updates
        const statusSelects = document.querySelectorAll('[data-action="update-status"]');
        statusSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                const leadId = e.target.getAttribute('data-lead-id');
                const status = e.target.value;
                this.updateLeadStatus(leadId, status);
            });
        });
    }

    /**
     * Contact a lead
     */
    async contactLead(leadId) {
        try {
            const response = await AdminAjax.post(`/admin/leads/${leadId}/contact`);
            
            if (response.success) {
                AdminUtils.showToast('Lead contacted successfully', 'success');
                // Refresh the lead list or update the UI
                this.refreshLeadList();
            } else {
                AdminUtils.showToast('Failed to contact lead', 'error');
            }
        } catch (error) {
            console.error('Failed to contact lead:', error);
            AdminUtils.showToast('Failed to contact lead', 'error');
        }
    }

    /**
     * Update lead status
     */
    async updateLeadStatus(leadId, status) {
        try {
            const response = await AdminAjax.put(`/admin/leads/${leadId}/status`, {
                status: status
            });
            
            if (response.success) {
                AdminUtils.showToast('Lead status updated', 'success');
                this.refreshLeadList();
            } else {
                AdminUtils.showToast('Failed to update lead status', 'error');
            }
        } catch (error) {
            console.error('Failed to update lead status:', error);
            AdminUtils.showToast('Failed to update lead status', 'error');
        }
    }

    /**
     * Refresh lead list
     */
    async refreshLeadList() {
        const leadContainer = document.querySelector('.recent-leads-container');
        if (!leadContainer) return;

        const loadingOverlay = AdminUtils.showLoading(leadContainer);

        try {
            const response = await AdminAjax.get('/admin/api/recent-leads');
            
            if (response.success) {
                this.updateLeadList(response.data);
            }
        } catch (error) {
            console.error('Failed to refresh lead list:', error);
        } finally {
            AdminUtils.hideLoading(loadingOverlay);
        }
    }

    /**
     * Update lead list in the UI
     */
    updateLeadList(leads) {
        const tbody = document.querySelector('.recent-leads-container tbody');
        if (!tbody) return;

        tbody.innerHTML = '';

        if (leads.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="5" class="text-center text-muted py-4">
                        No recent leads
                    </td>
                </tr>
            `;
            return;
        }

        leads.forEach(lead => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-2">
                            <div class="avatar-title bg-light text-dark rounded-circle">
                                ${lead.name.charAt(0)}
                            </div>
                        </div>
                        <div>
                            <h6 class="mb-0">${lead.name}</h6>
                            <small class="text-muted">${lead.email}</small>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge bg-light text-dark">${lead.type}</span>
                </td>
                <td>
                    <span class="badge bg-${lead.status_color}">${lead.status}</span>
                </td>
                <td>${lead.created_at}</td>
                <td>
                    <div class="btn-group btn-group-sm">
                        <a href="/admin/leads/${lead.id}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-outline-success btn-sm" 
                                data-action="contact-lead" 
                                data-lead-id="${lead.id}">
                            <i class="bi bi-telephone"></i>
                        </button>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });

        // Re-bind events for new elements
        this.initLeadManagement();
    }

    /**
     * Get default chart data
     */
    getDefaultChartData() {
        return {
            registration: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                students: [10, 15, 12, 18, 20, 8, 14],
                teachers: [3, 5, 4, 7, 6, 2, 5],
                institutes: [1, 2, 1, 2, 3, 1, 2]
            },
            distribution: [65, 25, 8, 2]
        };
    }

    /**
     * Bind additional events
     */
    bindEvents() {
        // Refresh button
        const refreshBtn = document.querySelector('[data-action="refresh-dashboard"]');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', () => {
                this.refreshDashboard();
            });
        }

        // Export data button
        const exportBtn = document.querySelector('[data-action="export-data"]');
        if (exportBtn) {
            exportBtn.addEventListener('click', () => {
                this.exportDashboardData();
            });
        }

        // Window resize event for chart responsiveness
        window.addEventListener('resize', AdminUtils.throttle(() => {
            Object.values(this.charts).forEach(chart => {
                if (chart && typeof chart.resize === 'function') {
                    chart.resize();
                }
            });
        }, 250));
    }

    /**
     * Refresh entire dashboard
     */
    async refreshDashboard() {
        AdminUtils.showToast('Refreshing dashboard...', 'info', 2000);
        
        try {
            await Promise.all([
                this.updateStatistics(),
                this.updateChartData('week'),
                this.refreshLeadList()
            ]);
            
            AdminUtils.showToast('Dashboard refreshed successfully', 'success');
        } catch (error) {
            console.error('Failed to refresh dashboard:', error);
            AdminUtils.showToast('Failed to refresh dashboard', 'error');
        }
    }

    /**
     * Export dashboard data
     */
    async exportDashboardData() {
        try {
            const response = await AdminAjax.get('/admin/api/export-dashboard');
            
            if (response.success) {
                // Create download link
                const link = document.createElement('a');
                link.href = response.download_url;
                link.download = 'dashboard-data.xlsx';
                link.click();
                
                AdminUtils.showToast('Dashboard data exported successfully', 'success');
            }
        } catch (error) {
            console.error('Failed to export data:', error);
            AdminUtils.showToast('Failed to export dashboard data', 'error');
        }
    }

    /**
     * Destroy dashboard instance
     */
    destroy() {
        // Clear intervals
        if (this.refreshInterval) {
            clearInterval(this.refreshInterval);
        }

        // Destroy charts
        Object.values(this.charts).forEach(chart => {
            if (chart && typeof chart.destroy === 'function') {
                chart.destroy();
            }
        });

        this.charts = {};
    }
}

/**
 * Global function for lead contact (called from blade template)
 */
window.contactLead = function(leadId) {
    if (window.dashboardManager) {
        window.dashboardManager.contactLead(leadId);
    } else {
        console.error('Dashboard manager not initialized');
    }
};

/**
 * Initialize dashboard when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', () => {
    // Only initialize on dashboard page
    if (document.getElementById('userRegistrationChart') || 
        document.querySelector('.stat-card')) {
        window.dashboardManager = new DashboardManager();
    }
});

/**
 * Cleanup on page unload
 */
window.addEventListener('beforeunload', () => {
    if (window.dashboardManager) {
        window.dashboardManager.destroy();
    }
});

/**
 * Export for module usage
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DashboardManager;
} 