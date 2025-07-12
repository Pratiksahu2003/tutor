@extends('admin.layout')

@section('title', 'Media Library')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="#">CMS</a></li>
    <li class="breadcrumb-item active">Media Library</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-images me-2"></i>
                    Media Library
                </h1>
                <p class="text-muted">Manage your website media files</p>
            </div>
            <div>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-upload me-2"></i>Upload Files
                </button>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <!-- Folder Navigation -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Folders</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    <a href="{{ route('admin.cms.media.index') }}" class="list-group-item list-group-item-action {{ !request('folder') ? 'active' : '' }}">
                        <i class="fas fa-folder me-2"></i>All Files
                    </a>
                    @foreach($folders ?? [] as $folder)
                        <a href="{{ route('admin.cms.media.index', ['folder' => $folder]) }}" 
                           class="list-group-item list-group-item-action {{ request('folder') == $folder ? 'active' : '' }}">
                            <i class="fas fa-folder me-2"></i>{{ $folder }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-9">
        <!-- Media Files Grid -->
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        @if(request('folder'))
                            Files in "{{ request('folder') }}"
                        @else
                            All Media Files
                        @endif
                    </h5>
                    <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="gridView">
                            <i class="fas fa-th"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="listView">
                            <i class="fas fa-list"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row" id="mediaGrid">
                    @forelse($files ?? [] as $file)
                        <div class="col-md-3 col-sm-4 col-6 mb-3">
                            <div class="card media-item">
                                <div class="card-body text-center p-2">
                                    @if(in_array($file['type'], ['image/jpeg', 'image/png', 'image/gif', 'image/webp']))
                                        <img src="{{ $file['url'] }}" alt="{{ $file['name'] }}" 
                                             class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                             style="height: 120px;">
                                            <i class="fas fa-file text-muted fa-2x"></i>
                                        </div>
                                    @endif
                                    <div class="mt-2">
                                        <small class="text-muted d-block">{{ Str::limit($file['name'], 20) }}</small>
                                        <small class="text-muted">{{ number_format($file['size'] / 1024, 1) }} KB</small>
                                    </div>
                                </div>
                                <div class="card-footer p-1">
                                    <div class="btn-group btn-group-sm w-100">
                                        <a href="{{ $file['url'] }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ $file['url'] }}" download class="btn btn-outline-secondary btn-sm">
                                            <i class="fas fa-download"></i>
                                        </a>
                                        <button type="button" class="btn btn-outline-danger btn-sm delete-file" 
                                                data-file="{{ $file['name'] }}" data-path="{{ $file['path'] }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No files found in this folder.</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                    <i class="fas fa-upload me-2"></i>Upload Files
                                </button>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Files</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="uploadForm" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="files" class="form-label">Select Files</label>
                        <input type="file" class="form-control" id="files" name="files[]" multiple accept="image/*,video/*,application/pdf">
                        <div class="form-text">Maximum file size: 10MB per file</div>
                    </div>
                    <div class="mb-3">
                        <label for="folder" class="form-label">Folder (Optional)</label>
                        <input type="text" class="form-control" id="folder" name="folder" 
                               value="{{ request('folder') }}" placeholder="Enter folder name">
                    </div>
                    <div id="uploadProgress" class="progress d-none">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload Files</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // File upload handling
    $('#uploadForm').on('submit', function(e) {
        e.preventDefault();
        
        var formData = new FormData(this);
        var $progress = $('#uploadProgress');
        var $progressBar = $progress.find('.progress-bar');
        
        $progress.removeClass('d-none');
        $progressBar.css('width', '0%');
        
        $.ajax({
            url: '{{ route("admin.cms.media.upload") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if (evt.lengthComputable) {
                        var percentComplete = evt.loaded / evt.total;
                        $progressBar.css('width', percentComplete * 100 + '%');
                    }
                }, false);
                return xhr;
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Upload failed: ' + response.message);
                }
            },
            error: function() {
                alert('Upload failed. Please try again.');
            },
            complete: function() {
                $progress.addClass('d-none');
                $('#uploadModal').modal('hide');
            }
        });
    });
    
    // Delete file handling
    $('.delete-file').click(function() {
        var fileName = $(this).data('file');
        var filePath = $(this).data('path');
        
        if (confirm('Are you sure you want to delete "' + fileName + '"?')) {
            $.ajax({
                url: '{{ route("admin.cms.media.delete") }}',
                type: 'DELETE',
                data: {
                    path: filePath,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Delete failed: ' + response.message);
                    }
                },
                error: function() {
                    alert('Delete failed. Please try again.');
                }
            });
        }
    });
    
    // View toggle
    $('#gridView').click(function() {
        $('#mediaGrid').removeClass('list-view').addClass('grid-view');
        $(this).addClass('active');
        $('#listView').removeClass('active');
    });
    
    $('#listView').click(function() {
        $('#mediaGrid').removeClass('grid-view').addClass('list-view');
        $(this).addClass('active');
        $('#gridView').removeClass('active');
    });
});
</script>
@endpush

@push('styles')
<style>
.media-item {
    transition: transform 0.2s;
}

.media-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.list-view .media-item {
    margin-bottom: 1rem;
}

.list-view .col-md-3 {
    flex: 0 0 100%;
    max-width: 100%;
}
</style>
@endpush 