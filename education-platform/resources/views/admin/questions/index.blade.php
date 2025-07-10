@extends('admin.layout')

@section('title', 'Questions Management')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Questions</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="page-title">
                    <i class="fas fa-question-circle me-2"></i>
                    Questions Management
                </h1>
                <p class="text-muted">Manage questions for quizzes, exams, and assessments</p>
            </div>
            <div>
                <a href="{{ route('admin.questions.statistics') }}" class="btn btn-info me-2">
                    <i class="fas fa-chart-bar me-2"></i>Statistics
                </a>
                <a href="{{ route('admin.questions.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add New Question
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.questions.index') }}">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label for="subject_id" class="form-label">Subject</label>
                            <select name="subject_id" id="subject_id" class="form-select">
                                <option value="">All Subjects</option>
                                @foreach($subjects as $subject)
                                    <option value="{{ $subject->id }}" {{ request('subject_id') == $subject->id ? 'selected' : '' }}>
                                        {{ $subject->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Status</label>
                            <select name="status" id="status" class="form-select">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                                <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="type" class="form-label">Type</label>
                            <select name="type" id="type" class="form-select">
                                <option value="">All Types</option>
                                <option value="multiple_choice" {{ request('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="true_false" {{ request('type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                                <option value="short_answer" {{ request('type') == 'short_answer' ? 'selected' : '' }}>Short Answer</option>
                                <option value="long_answer" {{ request('type') == 'long_answer' ? 'selected' : '' }}>Long Answer</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="difficulty" class="form-label">Difficulty</label>
                            <select name="difficulty" id="difficulty" class="form-select">
                                <option value="">All Levels</option>
                                <option value="easy" {{ request('difficulty') == 'easy' ? 'selected' : '' }}>Easy</option>
                                <option value="medium" {{ request('difficulty') == 'medium' ? 'selected' : '' }}>Medium</option>
                                <option value="hard" {{ request('difficulty') == 'hard' ? 'selected' : '' }}>Hard</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="class_level" class="form-label">Class</label>
                            <select name="class_level" id="class_level" class="form-select">
                                <option value="">All Classes</option>
                                @foreach($classLevels as $level)
                                    <option value="{{ $level }}" {{ request('class_level') == $level ? 'selected' : '' }}>
                                        {{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search me-2"></i>Filter
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-10">
                            <input type="text" name="search" id="search" class="form-control" 
                                   placeholder="Search questions by title, text, topic, or chapter..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <div class="d-grid">
                                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-times me-2"></i>Clear
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bulk Actions -->
<div class="row mb-3">
    <div class="col-12">
        <form id="bulkActionForm" method="POST" action="{{ route('admin.questions.bulk-action') }}">
            @csrf
            <div class="d-flex align-items-center gap-2">
                <select name="action" class="form-select" style="width: auto;">
                    <option value="">Bulk Actions</option>
                    <option value="publish">Publish Selected</option>
                    <option value="archive">Archive Selected</option>
                    <option value="mark_review">Mark for Review</option>
                    <option value="delete">Delete Selected</option>
                </select>
                <button type="submit" class="btn btn-outline-primary">Apply</button>
                <span class="text-muted ms-3" id="selectedCount">0 items selected</span>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">All Questions ({{ $questions->total() }} total)</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                </th>
                                <th>Question</th>
                                <th>Subject</th>
                                <th>Type</th>
                                <th>Difficulty</th>
                                <th>Marks</th>
                                <th>Class</th>
                                <th>Status</th>
                                <th>Creator</th>
                                <th>Usage</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($questions as $question)
                            <tr>
                                <td>
                                    <input type="checkbox" name="question_ids[]" value="{{ $question->id }}" 
                                           class="form-check-input question-checkbox">
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ Str::limit($question->title, 50) }}</strong>
                                        <br>
                                        <small class="text-muted">{{ Str::limit(strip_tags($question->question_text), 80) }}</small>
                                        @if($question->topic)
                                            <br><span class="badge bg-light text-dark">{{ $question->topic }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $question->subject->name ?? 'N/A' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $question->type_display }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-{{ $question->difficulty_color }}">
                                        {{ ucfirst($question->difficulty) }}
                                    </span>
                                </td>
                                <td>{{ $question->marks }}</td>
                                <td>{{ ucfirst($question->class_level ?? 'Any') }}</td>
                                <td>
                                    <span class="badge bg-{{ $question->status_color }}">
                                        {{ ucfirst($question->status) }}
                                    </span>
                                </td>
                                <td>{{ $question->creator->name ?? 'Unknown' }}</td>
                                <td>
                                    <small>Used: {{ $question->usage_count }}</small><br>
                                    @if($question->success_rate)
                                        <small>Success: {{ number_format($question->success_rate, 1) }}%</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.questions.show', $question) }}" class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($question->status !== 'published')
                                        <form method="POST" action="{{ route('admin.questions.publish', $question) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fas fa-check"></i>
                                            </button>
                                        </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.questions.destroy', $question) }}" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger delete-btn">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="11" class="text-center">
                                    <p class="text-muted">No questions found. <a href="{{ route('admin.questions.create') }}">Create one now</a></p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-center mt-4">
                    {{ $questions->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Select all functionality
    $('#selectAll').change(function() {
        $('.question-checkbox').prop('checked', this.checked);
        updateSelectedCount();
    });
    
    $('.question-checkbox').change(function() {
        updateSelectedCount();
    });
    
    function updateSelectedCount() {
        const count = $('.question-checkbox:checked').length;
        $('#selectedCount').text(count + ' items selected');
        
        // Update bulk action form
        const checkedBoxes = $('.question-checkbox:checked');
        $('#bulkActionForm input[name="question_ids[]"]').remove();
        checkedBoxes.each(function() {
            $('#bulkActionForm').append('<input type="hidden" name="question_ids[]" value="' + $(this).val() + '">');
        });
    }
    
    // Confirm bulk actions
    $('#bulkActionForm').submit(function(e) {
        const action = $('select[name="action"]').val();
        const count = $('.question-checkbox:checked').length;
        
        if (!action) {
            e.preventDefault();
            alert('Please select an action');
            return;
        }
        
        if (count === 0) {
            e.preventDefault();
            alert('Please select at least one question');
            return;
        }
        
        if (action === 'delete') {
            if (!confirm('Are you sure you want to delete ' + count + ' questions? This action cannot be undone.')) {
                e.preventDefault();
            }
        } else {
            if (!confirm('Are you sure you want to ' + action + ' ' + count + ' questions?')) {
                e.preventDefault();
            }
        }
    });
    
    // Confirm delete actions
    $('.delete-btn').click(function(e) {
        if (!confirm('Are you sure you want to delete this question?')) {
            e.preventDefault();
        }
    });
});
</script>
@endpush 