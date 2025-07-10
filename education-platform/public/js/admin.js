/**
 * Admin Dashboard JavaScript
 * Handles sidebar, theme switching, notifications, and common admin functionality
 */

class AdminDashboard {
    constructor() {
        this.init();
    }

    init() {
        this.initSidebar();
        this.initTheme();
        this.initNotifications();
        this.initAlerts();
        this.initSearch();
        this.bindEvents();
    }

    /**
     * Initialize sidebar functionality
     */
    initSidebar() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebar = document.getElementById('sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', () => {
                sidebar.classList.toggle('show');
            });

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', (e) => {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });
        }

        // Handle submenu toggles
        this.initSubmenuToggles();
    }

    /**
     * Initialize submenu toggles
     */
    initSubmenuToggles() {
        const submenuToggles = document.querySelectorAll('[data-bs-toggle="collapse"]');
        
        submenuToggles.forEach(toggle => {
            toggle.addEventListener('click', (e) => {
                e.preventDefault();
                
                const target = document.querySelector(toggle.getAttribute('data-bs-target'));
                const icon = toggle.querySelector('.bi-chevron-down');
                
                if (target) {
                    if (target.classList.contains('show')) {
                        target.classList.remove('show');
                        if (icon) icon.style.transform = 'rotate(0deg)';
                    } else {
                        // Close other open submenus
                        document.querySelectorAll('.nav-submenu.show').forEach(submenu => {
                            submenu.classList.remove('show');
                        });
                        
                        target.classList.add('show');
                        if (icon) icon.style.transform = 'rotate(180deg)';
                    }
                }
            });
        });
    }

    /**
     * Initialize theme switching
     */
    initTheme() {
        const themeToggle = document.getElementById('themeToggle');
        const html = document.documentElement;
        
        if (themeToggle) {
            themeToggle.addEventListener('click', () => {
                const currentTheme = html.getAttribute('data-bs-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                
                html.setAttribute('data-bs-theme', newTheme);
                
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    icon.className = newTheme === 'dark' ? 'bi bi-moon' : 'bi bi-sun';
                }
                
                localStorage.setItem('admin-theme', newTheme);
                
                // Trigger theme change event
                window.dispatchEvent(new CustomEvent('themeChanged', {
                    detail: { theme: newTheme }
                }));
            });
            
            // Load saved theme
            const savedTheme = localStorage.getItem('admin-theme');
            if (savedTheme) {
                html.setAttribute('data-bs-theme', savedTheme);
                const icon = themeToggle.querySelector('i');
                if (icon) {
                    icon.className = savedTheme === 'dark' ? 'bi bi-moon' : 'bi bi-sun';
                }
            }
        }
    }

    /**
     * Initialize notifications
     */
    initNotifications() {
        const notificationBtn = document.getElementById('notificationBtn');
        
        if (notificationBtn) {
            notificationBtn.addEventListener('click', () => {
                this.loadNotifications();
            });
        }

        // Check for new notifications periodically
        this.startNotificationPolling();
    }

    /**
     * Load notifications from server
     */
    async loadNotifications() {
        try {
            const response = await fetch('/api/notifications');
            const data = await response.json();
            
            if (data.success) {
                this.displayNotifications(data.notifications);
                this.updateNotificationBadge(data.unread_count);
            }
        } catch (error) {
            console.error('Failed to load notifications:', error);
        }
    }

    /**
     * Display notifications in dropdown/modal
     */
    displayNotifications(notifications) {
        // Implementation for displaying notifications
        // This would create a dropdown or modal with notifications
        console.log('Notifications:', notifications);
    }

    /**
     * Update notification badge count
     */
    updateNotificationBadge(count) {
        const badge = document.querySelector('.notification-badge');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'flex' : 'none';
        }
    }

    /**
     * Start polling for new notifications
     */
    startNotificationPolling() {
        // Poll every 30 seconds
        setInterval(() => {
            this.loadNotifications();
        }, 30000);
    }

    /**
     * Initialize auto-dismissing alerts
     */
    initAlerts() {
        const alerts = document.querySelectorAll('.alert-dismissible');
        
        alerts.forEach(alert => {
            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alert && alert.parentNode) {
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateX(100%)';
                    
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.parentNode.removeChild(alert);
                        }
                    }, 300);
                }
            }, 5000);
        });
    }

    /**
     * Initialize search functionality
     */
    initSearch() {
        const searchInput = document.querySelector('.topbar-search input');
        
        if (searchInput) {
            let searchTimeout;
            
            searchInput.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                const query = e.target.value.trim();
                
                if (query.length >= 2) {
                    searchTimeout = setTimeout(() => {
                        this.performSearch(query);
                    }, 300);
                } else {
                    this.hideSearchResults();
                }
            });

            // Handle search form submission
            const searchForm = searchInput.closest('form');
            if (searchForm) {
                searchForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const query = searchInput.value.trim();
                    if (query) {
                        this.performSearch(query);
                    }
                });
            }
        }
    }

    /**
     * Perform search and show results
     */
    async performSearch(query) {
        try {
            const response = await fetch(`/api/admin/search?q=${encodeURIComponent(query)}`);
            const data = await response.json();
            
            if (data.success) {
                this.displaySearchResults(data.results);
            }
        } catch (error) {
            console.error('Search failed:', error);
        }
    }

    /**
     * Display search results
     */
    displaySearchResults(results) {
        // Implementation for displaying search results
        console.log('Search results:', results);
    }

    /**
     * Hide search results
     */
    hideSearchResults() {
        const resultsContainer = document.querySelector('.search-results');
        if (resultsContainer) {
            resultsContainer.style.display = 'none';
        }
    }

    /**
     * Bind additional events
     */
    bindEvents() {
        // Handle window resize
        window.addEventListener('resize', () => {
            this.handleResize();
        });

        // Handle click outside dropdowns
        document.addEventListener('click', (e) => {
            this.handleOutsideClick(e);
        });

        // Handle keyboard shortcuts
        document.addEventListener('keydown', (e) => {
            this.handleKeyboardShortcuts(e);
        });
    }

    /**
     * Handle window resize
     */
    handleResize() {
        const sidebar = document.getElementById('sidebar');
        
        if (window.innerWidth > 768) {
            if (sidebar) {
                sidebar.classList.remove('show');
            }
        }
    }

    /**
     * Handle clicks outside dropdowns and modals
     */
    handleOutsideClick(e) {
        // Close search results if clicking outside
        const searchContainer = document.querySelector('.topbar-search');
        const resultsContainer = document.querySelector('.search-results');
        
        if (resultsContainer && !searchContainer.contains(e.target)) {
            this.hideSearchResults();
        }
    }

    /**
     * Handle keyboard shortcuts
     */
    handleKeyboardShortcuts(e) {
        // Ctrl/Cmd + K for search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.querySelector('.topbar-search input');
            if (searchInput) {
                searchInput.focus();
            }
        }

        // Escape to close modals/dropdowns
        if (e.key === 'Escape') {
            this.hideSearchResults();
            // Close any open dropdowns
            const openDropdowns = document.querySelectorAll('.dropdown-menu.show');
            openDropdowns.forEach(dropdown => {
                dropdown.classList.remove('show');
            });
        }
    }
}

/**
 * Utility functions
 */
const AdminUtils = {
    /**
     * Format currency
     */
    formatCurrency(amount, currency = '₹') {
        return `${currency}${parseFloat(amount).toLocaleString('en-IN')}`;
    },

    /**
     * Format date
     */
    formatDate(date, format = 'short') {
        const d = new Date(date);
        
        if (format === 'short') {
            return d.toLocaleDateString('en-IN');
        } else if (format === 'long') {
            return d.toLocaleDateString('en-IN', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        } else if (format === 'time') {
            return d.toLocaleTimeString('en-IN', {
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        return d.toLocaleString('en-IN');
    },

    /**
     * Show toast notification
     */
    showToast(message, type = 'info', duration = 3000) {
        const toast = document.createElement('div');
        toast.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        
        toast.innerHTML = `
            <strong>${type.charAt(0).toUpperCase() + type.slice(1)}!</strong> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after duration
        setTimeout(() => {
            if (toast.parentNode) {
                toast.remove();
            }
        }, duration);
    },

    /**
     * Show loading overlay
     */
    showLoading(container) {
        const overlay = document.createElement('div');
        overlay.className = 'loading-overlay';
        overlay.innerHTML = `
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        `;
        
        container.style.position = 'relative';
        container.appendChild(overlay);
        
        return overlay;
    },

    /**
     * Hide loading overlay
     */
    hideLoading(overlay) {
        if (overlay && overlay.parentNode) {
            overlay.parentNode.removeChild(overlay);
        }
    },

    /**
     * Confirm action with modal
     */
    confirmAction(message, callback) {
        if (confirm(message)) {
            callback();
        }
    },

    /**
     * Copy text to clipboard
     */
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            this.showToast('Copied to clipboard!', 'success', 2000);
        } catch (error) {
            console.error('Failed to copy:', error);
            this.showToast('Failed to copy to clipboard', 'error', 2000);
        }
    },

    /**
     * Validate email
     */
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    /**
     * Validate phone number (Indian format)
     */
    isValidPhone(phone) {
        const phoneRegex = /^[+]?[0-9]{10,13}$/;
        return phoneRegex.test(phone.replace(/\s+/g, ''));
    },

    /**
     * Debounce function
     */
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    /**
     * Throttle function
     */
    throttle(func, limit) {
        let inThrottle;
        return function(...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    }
};

/**
 * AJAX Helper
 */
const AdminAjax = {
    /**
     * Make GET request
     */
    async get(url, options = {}) {
        return this.request('GET', url, null, options);
    },

    /**
     * Make POST request
     */
    async post(url, data, options = {}) {
        return this.request('POST', url, data, options);
    },

    /**
     * Make PUT request
     */
    async put(url, data, options = {}) {
        return this.request('PUT', url, data, options);
    },

    /**
     * Make DELETE request
     */
    async delete(url, options = {}) {
        return this.request('DELETE', url, null, options);
    },

    /**
     * Generic request method
     */
    async request(method, url, data = null, options = {}) {
        const defaultOptions = {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        };

        if (data) {
            defaultOptions.body = JSON.stringify(data);
        }

        const finalOptions = { ...defaultOptions, ...options };

        try {
            const response = await fetch(url, finalOptions);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('AJAX request failed:', error);
            throw error;
        }
    }
};

/**
 * Initialize admin dashboard when DOM is loaded
 */
document.addEventListener('DOMContentLoaded', () => {
    new AdminDashboard();
    
    // Make utilities globally available
    window.AdminUtils = AdminUtils;
    window.AdminAjax = AdminAjax;
});

/**
 * Export for module usage
 */
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { AdminDashboard, AdminUtils, AdminAjax };
} 