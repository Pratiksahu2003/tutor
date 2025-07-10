@extends('admin.layout')

@section('title', 'Create Question')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
    <li class="breadcrumb-item active">Create</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="page-title">
            <i class="fas fa-plus me-2"></i>
            Create New Question
        </h1>
    </div>
</div>

<form method="POST" action="{{ route('admin.questions.store') }}" enctype="multipart/form-data">
    @csrf
    
    <div class="row">
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Question Title</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="question_text" class="form-label">Question Text</label>
                        <textarea class="form-control @error('question_text') is-invalid @enderror" 
                                  id="question_text" name="question_text" rows="5" required>{{ old('question_text') }}</textarea>
                        @error('question_text')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="type" class="form-label">Question Type</label>
                                <select class="form-select @error('type') is-invalid @enderror" id="type" name="type" required>
                                    <option value="">Select Type</option>
                                    @foreach($questionTypes as $value => $label)
                                        <option value="{{ $value }}" {{ old('type') == $value ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="difficulty" class="form-label">Difficulty</label>
                                <select class="form-select @error('difficulty') is-invalid @enderror" id="difficulty" name="difficulty" required>
                                    <option value="">Select Difficulty</option>
                                    @foreach($difficulties as $difficulty)
                                        <option value="{{ $difficulty }}" {{ old('difficulty') == $difficulty ? 'selected' : '' }}>
                                            {{ ucfirst($difficulty) }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('difficulty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="marks" class="form-label">Marks</label>
                                <input type="number" class="form-control @error('marks') is-invalid @enderror" 
                                       id="marks" name="marks" value="{{ old('marks', 1) }}" min="1" max="100" required>
                                @error('marks')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="time_limit" class="form-label">Time Limit (seconds)</label>
                                <input type="number" class="form-control @error('time_limit') is-invalid @enderror" 
                                       id="time_limit" name="time_limit" value="{{ old('time_limit') }}" min="1">
                                @error('time_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Leave empty for no time limit</small>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="image" class="form-label">Question Image (Optional)</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" 
                               id="image" name="image" accept="image/*">
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Options and Answers -->
            <div class="card mb-4" id="optionsCard" style="display: none;">
                <div class="card-header">
                    <h5 class="mb-0">Answer Options</h5>
                </div>
                <div class="card-body">
                    <div id="optionsContainer">
                        <!-- Dynamic options will be added here -->
                    </div>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addOption">
                        <i class="fas fa-plus me-2"></i>Add Option
                    </button>
                </div>
            </div>

            <!-- Additional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Additional Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="explanation" class="form-label">Explanation</label>
                        <textarea class="form-control @error('explanation') is-invalid @enderror" 
                                  id="explanation" name="explanation" rows="3">{{ old('explanation') }}</textarea>
                        @error('explanation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="hint" class="form-label">Hint</label>
                        <textarea class="form-control @error('hint') is-invalid @enderror" 
                                  id="hint" name="hint" rows="2">{{ old('hint') }}</textarea>
                        @error('hint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="solution_steps" class="form-label">Solution Steps</label>
                        <textarea class="form-control @error('solution_steps') is-invalid @enderror" 
                                  id="solution_steps" name="solution_steps" rows="4">{{ old('solution_steps') }}</textarea>
                        @error('solution_steps')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Academic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Academic Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="subject_id" class="form-label">Subject</label>
                        <select class="form-select @error('subject_id') is-invalid @enderror" id="subject_id" name="subject_id" required>
                            <option value="">Select Subject</option>
                            @foreach($subjects as $subject)
                                <option value="{{ $subject->id }}" {{ old('subject_id') == $subject->id ? 'selected' : '' }}>
                                    {{ $subject->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('subject_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="topic" class="form-label">Topic</label>
                        <input type="text" class="form-control @error('topic') is-invalid @enderror" 
                               id="topic" name="topic" value="{{ old('topic') }}">
                        @error('topic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subtopic" class="form-label">Subtopic</label>
                        <input type="text" class="form-control @error('subtopic') is-invalid @enderror" 
                               id="subtopic" name="subtopic" value="{{ old('subtopic') }}">
                        @error('subtopic')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="chapter" class="form-label">Chapter</label>
                        <input type="text" class="form-control @error('chapter') is-invalid @enderror" 
                               id="chapter" name="chapter" value="{{ old('chapter') }}">
                        @error('chapter')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="class_level" class="form-label">Class Level</label>
                        <select class="form-select @error('class_level') is-invalid @enderror" id="class_level" name="class_level">
                            <option value="">Select Class</option>
                            @foreach($classLevels as $level)
                                <option value="{{ $level }}" {{ old('class_level') == $level ? 'selected' : '' }}>
                                    {{ ucfirst($level) }}
                                </option>
                            @endforeach
                        </select>
                        @error('class_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="board" class="form-label">Board</label>
                        <select class="form-select @error('board') is-invalid @enderror" id="board" name="board">
                            <option value="">Select Board</option>
                            @foreach($boards as $board)
                                <option value="{{ $board }}" {{ old('board') == $board ? 'selected' : '' }}>
                                    {{ $board }}
                                </option>
                            @endforeach
                        </select>
                        @error('board')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Classification -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Classification</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <select class="form-select @error('category') is-invalid @enderror" id="category" name="category" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ old('category') == $category ? 'selected' : '' }}>
                                    {{ ucfirst($category) }}
                                </option>
                            @endforeach
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control @error('tags') is-invalid @enderror" 
                               id="tags" name="tags" value="{{ old('tags') }}" 
                               placeholder="Enter tags separated by commas">
                        @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="source" class="form-label">Source</label>
                        <input type="text" class="form-control @error('source') is-invalid @enderror" 
                               id="source" name="source" value="{{ old('source') }}" 
                               placeholder="e.g., NCERT, Previous Year Paper">
                        @error('source')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="reference" class="form-label">Reference</label>
                        <input type="text" class="form-control @error('reference') is-invalid @enderror" 
                               id="reference" name="reference" value="{{ old('reference') }}" 
                               placeholder="Book name, page number, etc.">
                        @error('reference')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Publishing Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Publishing Options</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                            <option value="draft" {{ old('status', 'draft') == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            <option value="under_review" {{ old('status') == 'under_review' ? 'selected' : '' }}>Under Review</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" 
                                   {{ old('is_public', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">
                                Public Question
                            </label>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="allow_comments" name="allow_comments" value="1" 
                                   {{ old('allow_comments', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="allow_comments">
                                Allow Comments
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Question
                        </button>
                        <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let optionCount = 0;
    
    // Show/hide options based on question type
    $('#type').change(function() {
        const type = $(this).val();
        
        if (type === 'multiple_choice' || type === 'true_false' || type === 'matching') {
            $('#optionsCard').show();
            setupOptions(type);
        } else {
            $('#optionsCard').hide();
        }
    });
    
    // Add option button
    $('#addOption').click(function() {
        addOption();
    });
    
    function setupOptions(type) {
        $('#optionsContainer').empty();
        optionCount = 0;
        
        if (type === 'true_false') {
            addOption('True');
            addOption('False');
            $('#addOption').hide();
        } else {
            $('#addOption').show();
            // Add default options
            addOption();
            addOption();
        }
    }
    
    function addOption(defaultValue = '') {
        optionCount++;
        const optionHtml = `
            <div class="option-row mb-3" data-option="${optionCount}">
                <div class="row">
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="options[]" 
                               placeholder="Option ${optionCount}" value="${defaultValue}">
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                   name="correct_answers[]" value="${optionCount - 1}">
                            <label class="form-check-label">Correct</label>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-option">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        $('#optionsContainer').append(optionHtml);
    }
    
    // Remove option
    $(document).on('click', '.remove-option', function() {
        $(this).closest('.option-row').remove();
        updateOptionIndexes();
    });
    
    function updateOptionIndexes() {
        $('#optionsContainer .option-row').each(function(index) {
            $(this).find('input[name="options[]"]').attr('placeholder', `Option ${index + 1}`);
            $(this).find('input[name="correct_answers[]"]').val(index);
        });
    }
    
    // Trigger on page load if type is already selected
    if ($('#type').val()) {
        $('#type').trigger('change');
    }
});
</script>
@endpush 