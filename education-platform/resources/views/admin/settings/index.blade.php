@extends('admin.layout')

@section('title', 'Site Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>Site Settings
                    </h3>
                    <div class="btn-group">
                        <button type="button" class="btn btn-outline-primary dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fas fa-tools me-1"></i>System Tools
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="clearCache('all')">
                                <i class="fas fa-broom me-2"></i>Clear All Cache
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="clearCache('config')">
                                <i class="fas fa-cog me-2"></i>Clear Config Cache
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="clearCache('route')">
                                <i class="fas fa-route me-2"></i>Clear Route Cache
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="clearCache('view')">
                                <i class="fas fa-eye me-2"></i>Clear View Cache
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="optimizeApp()">
                                <i class="fas fa-rocket me-2"></i>Optimize Application
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="showSystemInfo()">
                                <i class="fas fa-info-circle me-2"></i>System Information
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="backupDatabase()">
                                <i class="fas fa-download me-2"></i>Download Backup
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-warning" href="#" onclick="resetSettings()">
                                <i class="fas fa-undo me-2"></i>Reset to Defaults
                            </a></li>
                        </ul>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- Navigation Tabs -->
                    <ul class="nav nav-tabs" id="settingsTabs" role="tablist">
                        @foreach($groups as $groupKey => $groupName)
                            @if(isset($settings[$groupKey]) && $settings[$groupKey]->count() > 0)
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                            id="{{ $groupKey }}-tab" 
                                            data-bs-toggle="tab" 
                                            data-bs-target="#{{ $groupKey }}" 
                                            type="button" 
                                            role="tab">
                                        @switch($groupKey)
                                            @case('general')
                                                <i class="fas fa-home me-1"></i>
                                                @break
                                            @case('site')
                                                <i class="fas fa-globe me-1"></i>
                                                @break
                                            @case('cache')
                                                <i class="fas fa-database me-1"></i>
                                                @break
                                            @case('database')
                                                <i class="fas fa-server me-1"></i>
                                                @break
                                            @case('social')
                                                <i class="fas fa-share-alt me-1"></i>
                                                @break
                                            @case('content')
                                                <i class="fas fa-file-alt me-1"></i>
                                                @break
                                            @case('appearance')
                                                <i class="fas fa-palette me-1"></i>
                                                @break
                                            @case('system')
                                                <i class="fas fa-cogs me-1"></i>
                                                @break
                                            @default
                                                <i class="fas fa-cog me-1"></i>
                                        @endswitch
                                        {{ $groupName }}
                                    </button>
                                </li>
                            @endif
                        @endforeach
                    </ul>

                    <!-- Tab Content -->
                    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="tab-content mt-3" id="settingsTabContent">
                            @foreach($groups as $groupKey => $groupName)
                                @if(isset($settings[$groupKey]) && $settings[$groupKey]->count() > 0)
                                    <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" 
                                         id="{{ $groupKey }}" 
                                         role="tabpanel" 
                                         aria-labelledby="{{ $groupKey }}-tab">
                                        
                                        <div class="row">
                                            @foreach($settings[$groupKey] as $setting)
                                                <div class="col-md-6 mb-3">
                                                    <label for="{{ $setting->key }}" class="form-label">
                                                        {{ $setting->label }}
                                                        @if($setting->description)
                                                            <i class="fas fa-info-circle text-muted ms-1" 
                                                               title="{{ $setting->description }}" 
                                                               data-bs-toggle="tooltip"></i>
                                                        @endif
                                                    </label>
                                                    
                                                    @switch($setting->getInputType())
                                                        @case('checkbox')
                                                            <div class="form-check form-switch">
                                                                <input type="checkbox" 
                                                                       class="form-check-input" 
                                                                       id="{{ $setting->key }}" 
                                                                       name="{{ $setting->key }}" 
                                                                       value="1"
                                                                       {{ $setting->value ? 'checked' : '' }}>
                                                                <label class="form-check-label" for="{{ $setting->key }}">
                                                                    {{ $setting->value ? 'Enabled' : 'Disabled' }}
                                                                </label>
                                                            </div>
                                                            @break
                                                            
                                                        @case('textarea')
                                                            <textarea class="form-control" 
                                                                      id="{{ $setting->key }}" 
                                                                      name="{{ $setting->key }}" 
                                                                      rows="4"
                                                                      placeholder="{{ $setting->description }}">{{ $setting->value }}</textarea>
                                                            @break
                                                            
                                                        @case('file')
                                                            <input type="file" 
                                                                   class="form-control" 
                                                                   id="{{ $setting->key }}" 
                                                                   name="{{ $setting->key }}"
                                                                   accept="image/*">
                                                            @if($setting->value)
                                                                <div class="mt-2">
                                                                    <small class="text-muted">Current: {{ basename($setting->value) }}</small>
                                                                    @if(in_array(pathinfo($setting->value, PATHINFO_EXTENSION), ['jpg', 'jpeg', 'png', 'gif']))
                                                                        <div class="mt-1">
                                                                            <img src="{{ Storage::url($setting->value) }}" 
                                                                                 alt="Current {{ $setting->label }}" 
                                                                                 class="img-thumbnail" 
                                                                                 style="max-height: 60px;">
                                                                        </div>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                            @break
                                                            
                                                        @case('number')
                                                            <input type="number" 
                                                                   class="form-control" 
                                                                   id="{{ $setting->key }}" 
                                                                   name="{{ $setting->key }}" 
                                                                   value="{{ $setting->value }}"
                                                                   placeholder="{{ $setting->description }}">
                                                            @break
                                                            
                                                        @default
                                                            <input type="text" 
                                                                   class="form-control" 
                                                                   id="{{ $setting->key }}" 
                                                                   name="{{ $setting->key }}" 
                                                                   value="{{ $setting->value }}"
                                                                   placeholder="{{ $setting->description }}">
                                                    @endswitch
                                                    
                                                    @if($setting->description)
                                                        <div class="form-text">{{ $setting->description }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        
                        <div class="mt-4 pt-3 border-top">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i>Save Settings
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="location.reload()">
                                <i class="fas fa-undo me-1"></i>Reset Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Info Modal -->
<div class="modal fade" id="systemInfoModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>System Information
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="systemInfoContent">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});

// Clear cache function
function clearCache(type) {
    if(confirm('Are you sure you want to clear the ' + type + ' cache?')) {
        fetch('{{ route("admin.settings.clear-cache") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({type: type})
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Error clearing cache: ' + error.message);
        });
    }
}

// Optimize application
function optimizeApp() {
    if(confirm('This will optimize the application for production. Continue?')) {
        fetch('{{ route("admin.settings.optimize") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if(data.success) {
                showAlert('success', data.message);
            } else {
                showAlert('error', data.message);
            }
        })
        .catch(error => {
            showAlert('error', 'Error optimizing application: ' + error.message);
        });
    }
}

// Show system information
function showSystemInfo() {
    const modal = new bootstrap.Modal(document.getElementById('systemInfoModal'));
    modal.show();
    
    fetch('{{ route("admin.settings.system-info") }}')
        .then(response => response.json())
        .then(data => {
            let content = '<div class="table-responsive"><table class="table table-striped">';
            for(let key in data) {
                content += `<tr><td><strong>${key}</strong></td><td>${data[key]}</td></tr>`;
            }
            content += '</table></div>';
            document.getElementById('systemInfoContent').innerHTML = content;
        })
        .catch(error => {
            document.getElementById('systemInfoContent').innerHTML = 
                '<div class="alert alert-danger">Error loading system information: ' + error.message + '</div>';
        });
}

// Backup database
function backupDatabase() {
    if(confirm('This will create and download a database backup. Continue?')) {
        window.location.href = '{{ route("admin.settings.backup") }}';
    }
}

// Reset settings
function resetSettings() {
    if(confirm('This will reset all settings to their default values. This action cannot be undone. Continue?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.settings.reset") }}';
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// Show alert function
function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const iconClass = type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle';
    
    const alert = document.createElement('div');
    alert.className = `alert ${alertClass} alert-dismissible fade show`;
    alert.innerHTML = `
        <i class="fas ${iconClass} me-2"></i>${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    const container = document.querySelector('.card-body');
    container.insertBefore(alert, container.firstChild);
    
    // Auto dismiss after 5 seconds
    setTimeout(() => {
        if(alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}
</script>
@endsection 