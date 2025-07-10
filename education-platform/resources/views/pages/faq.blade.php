@extends('layouts.app')

@section('title', 'Frequently Asked Questions - Education Platform')
@section('meta_description', 'Find answers to commonly asked questions about our education platform, subjects, and learning process.')

@section('content')
<div class="container-fluid py-5">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="text-center bg-gradient-primary py-5 mb-5 rounded-3 text-white">
                <div class="container">
                    <h1 class="display-4 fw-bold mb-3">
                        <i class="bi bi-question-circle me-3"></i>
                        Frequently Asked Questions
                    </h1>
                    <p class="lead mb-4">Find answers to commonly asked questions about learning, subjects, and our platform</p>
                    
                    <!-- Quick Stats -->
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="row text-center">
                                <div class="col-6 col-md-3">
                                    <div class="h3 fw-bold">{{ $stats['total_questions'] ?? 0 }}</div>
                                    <small>Total Questions</small>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="h3 fw-bold">{{ $stats['total_subjects'] ?? 0 }}</div>
                                    <small>Subjects Covered</small>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="h3 fw-bold">{{ $stats['faq_questions'] ?? 0 }}</div>
                                    <small>FAQ Questions</small>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div class="h3 fw-bold">{{ $subjects->count() ?? 0 }}</div>
                                    <small>Active Subjects</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Search and Filter Section -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="searchQuestions" class="form-label fw-semibold">
                                    <i class="bi bi-search me-2"></i>Search Questions
                                </label>
                                <input type="text" class="form-control form-control-lg" id="searchQuestions" 
                                       placeholder="Type your question or keyword...">
                            </div>
                            <div class="col-md-3">
                                <label for="filterSubject" class="form-label fw-semibold">
                                    <i class="bi bi-book me-2"></i>Filter by Subject
                                </label>
                                <select class="form-select form-select-lg" id="filterSubject">
                                    <option value="">All Subjects</option>
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->name }}">{{ $subject->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="filterDifficulty" class="form-label fw-semibold">
                                    <i class="bi bi-speedometer2 me-2"></i>Difficulty Level
                                </label>
                                <select class="form-select form-select-lg" id="filterDifficulty">
                                    <option value="">All Levels</option>
                                    <option value="easy">Easy</option>
                                    <option value="medium">Medium</option>
                                    <option value="hard">Hard</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- General FAQ Section -->
        @if(isset($generalFaqs) && $generalFaqs->count() > 0)
        <div class="row mb-5">
            <div class="col-12">
                <h2 class="h3 fw-bold mb-4">
                    <i class="bi bi-info-circle text-primary me-2"></i>
                    General Platform Questions
                </h2>
                <div class="accordion" id="generalFaqAccordion">
                    @foreach($generalFaqs as $index => $faq)
                    <div class="accordion-item border-0 mb-3 shadow-sm faq-item" 
                         data-subject="{{ $faq->subject->name ?? 'General' }}" 
                         data-difficulty="{{ $faq->difficulty_level ?? 'medium' }}"
                         data-keywords="{{ strtolower(($faq->title ?? '') . ' ' . $faq->question_text . ' ' . ($faq->explanation ?? '')) }}">
                        <h2 class="accordion-header">
                            <button class="accordion-button {{ $index === 0 ? '' : 'collapsed' }}" 
                                    type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#generalFaq{{ $faq->id }}">
                                <div class="d-flex align-items-center w-100">
                                    <div class="flex-grow-1">
                                        <strong>{{ $faq->title ?? $faq->question_text }}</strong>
                                        @if($faq->subject)
                                            <span class="badge bg-primary ms-2">{{ $faq->subject->name }}</span>
                                        @endif
                                        <span class="badge bg-{{ ($faq->difficulty_level ?? 'medium') === 'easy' ? 'success' : (($faq->difficulty_level ?? 'medium') === 'medium' ? 'warning' : 'danger') }} ms-1">
                                            {{ ucfirst($faq->difficulty_level ?? 'medium') }}
                                        </span>
                                    </div>
                                </div>
                            </button>
                        </h2>
                        <div id="generalFaq{{ $faq->id }}" 
                             class="accordion-collapse collapse {{ $index === 0 ? 'show' : '' }}" 
                             data-bs-parent="#generalFaqAccordion">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-lg-8">
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-primary">Question:</h6>
                                            <p>{{ $faq->question_text }}</p>
                                        </div>
                                        
                                        @if($faq->explanation)
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-success">Answer:</h6>
                                            <p>{{ $faq->explanation }}</p>
                                        </div>
                                        @endif

                                        @if($faq->hint)
                                        <div class="mb-3">
                                            <h6 class="fw-semibold text-info">Hint:</h6>
                                            <p class="text-muted">{{ $faq->hint }}</p>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="bg-light p-3 rounded">
                                            <h6 class="fw-semibold mb-3">Question Details</h6>
                                            <div class="small">
                                                @if($faq->topic)
                                                <div class="mb-2">
                                                    <strong>Topic:</strong> {{ $faq->topic }}
                                                </div>
                                                @endif
                                                @if($faq->class_level)
                                                <div class="mb-2">
                                                    <strong>Class Level:</strong> {{ ucfirst($faq->class_level) }}
                                                </div>
                                                @endif
                                                @if($faq->board)
                                                <div class="mb-2">
                                                    <strong>Board:</strong> {{ $faq->board }}
                                                </div>
                                                @endif
                                                @if($faq->tags)
                                                <div class="mb-2">
                                                    <strong>Tags:</strong><br>
                                                    @if(is_array($faq->tags))
                                                        @foreach($faq->tags as $tag)
                                                            <span class="badge bg-secondary me-1 mb-1">{{ $tag }}</span>
                                                        @endforeach
                                                    @else
                                                        <span class="badge bg-secondary">{{ $faq->tags }}</span>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <!-- Subject-wise FAQ Questions -->
        @if(isset($faqQuestions) && $faqQuestions->count() > 0)
        <div class="row">
            <div class="col-12">
                <h2 class="h3 fw-bold mb-4">
                    <i class="bi bi-book text-primary me-2"></i>
                    Subject-wise Questions & Answers
                </h2>
                
                @foreach($faqQuestions as $subjectName => $questions)
                <div class="subject-section mb-5" data-subject="{{ $subjectName }}">
                    <h3 class="h4 fw-semibold mb-3 text-primary">
                        <i class="bi bi-collection me-2"></i>{{ $subjectName }}
                        <span class="badge bg-primary ms-2">{{ $questions->count() }} questions</span>
                    </h3>
                    
                    <div class="accordion" id="subject{{ Str::slug($subjectName) }}Accordion">
                        @foreach($questions as $qIndex => $question)
                        <div class="accordion-item border-0 mb-3 shadow-sm faq-item" 
                             data-subject="{{ $subjectName }}" 
                             data-difficulty="{{ $question->difficulty_level ?? 'medium' }}"
                             data-keywords="{{ strtolower(($question->title ?? '') . ' ' . $question->question_text . ' ' . ($question->explanation ?? '') . ' ' . ($question->topic ?? '')) }}">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#question{{ $question->id }}">
                                    <div class="d-flex align-items-center w-100">
                                        <div class="flex-grow-1">
                                            <strong>{{ $question->title ?? $question->question_text }}</strong>
                                            @if($question->topic)
                                                <span class="badge bg-info ms-2">{{ $question->topic }}</span>
                                            @endif
                                            <span class="badge bg-{{ ($question->difficulty_level ?? 'medium') === 'easy' ? 'success' : (($question->difficulty_level ?? 'medium') === 'medium' ? 'warning' : 'danger') }} ms-1">
                                                {{ ucfirst($question->difficulty_level ?? 'medium') }}
                                            </span>
                                            @if($question->marks)
                                                <span class="badge bg-secondary ms-1">{{ $question->marks }} marks</span>
                                            @endif
                                        </div>
                                    </div>
                                </button>
                            </h2>
                            <div id="question{{ $question->id }}" 
                                 class="accordion-collapse collapse" 
                                 data-bs-parent="#subject{{ Str::slug($subjectName) }}Accordion">
                                <div class="accordion-body">
                                    <div class="row">
                                        <div class="col-lg-8">
                                            <div class="mb-3">
                                                <h6 class="fw-semibold text-primary">Question:</h6>
                                                <p>{{ $question->question_text }}</p>
                                            </div>

                                            @if($question->options && count($question->options) > 0)
                                            <div class="mb-3">
                                                <h6 class="fw-semibold text-secondary">Options:</h6>
                                                <ul class="list-unstyled">
                                                    @foreach($question->options as $index => $option)
                                                    <li class="mb-2">
                                                        <span class="badge bg-light text-dark me-2">{{ chr(65 + $index) }}</span>
                                                        {{ $option }}
                                                        @if($question->correct_answers && in_array($index, $question->correct_answers))
                                                            <span class="badge bg-success ms-2">✓ Correct</span>
                                                        @endif
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                            @endif
                                            
                                            @if($question->explanation)
                                            <div class="mb-3">
                                                <h6 class="fw-semibold text-success">Explanation:</h6>
                                                <p>{{ $question->explanation }}</p>
                                            </div>
                                            @endif

                                            @if($question->hint)
                                            <div class="mb-3">
                                                <h6 class="fw-semibold text-info">Hint:</h6>
                                                <p class="text-muted">{{ $question->hint }}</p>
                                            </div>
                                            @endif

                                            @if($question->solution_steps)
                                            <div class="mb-3">
                                                <h6 class="fw-semibold text-warning">Solution Steps:</h6>
                                                <pre class="bg-light p-3 rounded">{{ $question->solution_steps }}</pre>
                                            </div>
                                            @endif
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="bg-light p-3 rounded">
                                                <h6 class="fw-semibold mb-3">Question Details</h6>
                                                <div class="small">
                                                    <div class="mb-2">
                                                        <strong>Subject:</strong> {{ $question->subject->name ?? 'N/A' }}
                                                    </div>
                                                    @if($question->topic)
                                                    <div class="mb-2">
                                                        <strong>Topic:</strong> {{ $question->topic }}
                                                    </div>
                                                    @endif
                                                    @if($question->chapter)
                                                    <div class="mb-2">
                                                        <strong>Chapter:</strong> {{ $question->chapter }}
                                                    </div>
                                                    @endif
                                                    @if($question->class_level)
                                                    <div class="mb-2">
                                                        <strong>Class Level:</strong> {{ ucfirst($question->class_level) }}
                                                    </div>
                                                    @endif
                                                    @if($question->board)
                                                    <div class="mb-2">
                                                        <strong>Board:</strong> {{ $question->board }}
                                                    </div>
                                                    @endif
                                                    <div class="mb-2">
                                                        <strong>Difficulty:</strong> 
                                                        <span class="badge bg-{{ ($question->difficulty_level ?? 'medium') === 'easy' ? 'success' : (($question->difficulty_level ?? 'medium') === 'medium' ? 'warning' : 'danger') }}">
                                                            {{ ucfirst($question->difficulty_level ?? 'medium') }}
                                                        </span>
                                                    </div>
                                                    @if($question->usage_count > 0)
                                                    <div class="mb-2">
                                                        <strong>Times Used:</strong> {{ $question->usage_count }}
                                                    </div>
                                                    @endif
                                                    @if($question->success_rate)
                                                    <div class="mb-2">
                                                        <strong>Success Rate:</strong> {{ number_format($question->success_rate, 1) }}%
                                                    </div>
                                                    @endif
                                                    @if($question->tags)
                                                    <div class="mb-2">
                                                        <strong>Tags:</strong><br>
                                                        @if(is_array($question->tags))
                                                            @foreach($question->tags as $tag)
                                                                <span class="badge bg-secondary me-1 mb-1">{{ $tag }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="badge bg-secondary">{{ $question->tags }}</span>
                                                        @endif
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- No Results Message -->
        <div id="noResults" class="row" style="display: none;">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="bi bi-search display-1 text-muted"></i>
                    <h3 class="mt-3">No questions found</h3>
                    <p class="text-muted">Try adjusting your search terms or filters</p>
                    <button class="btn btn-primary" onclick="clearAllFilters()">
                        <i class="bi bi-arrow-clockwise me-2"></i>Clear Filters
                    </button>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="bg-gradient-primary text-white p-5 rounded-3 text-center">
                    <h3 class="fw-bold mb-3">Still have questions?</h3>
                    <p class="lead mb-4">Can't find what you're looking for? Our support team is here to help!</p>
                    <div class="d-flex flex-wrap justify-content-center gap-3">
                        <a href="{{ route('contact') }}" class="btn btn-light btn-lg px-4">
                            <i class="bi bi-envelope me-2"></i>Contact Support
                        </a>
                        <a href="{{ route('teachers.index') }}" class="btn btn-outline-light btn-lg px-4">
                            <i class="bi bi-person-check me-2"></i>Find a Teacher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchQuestions').on('input', function() {
        filterQuestions();
    });
    
    // Subject filter
    $('#filterSubject').on('change', function() {
        filterQuestions();
    });
    
    // Difficulty filter
    $('#filterDifficulty').on('change', function() {
        filterQuestions();
    });
    
    function filterQuestions() {
        const searchTerm = $('#searchQuestions').val().toLowerCase();
        const selectedSubject = $('#filterSubject').val();
        const selectedDifficulty = $('#filterDifficulty').val();
        
        let visibleCount = 0;
        
        $('.faq-item').each(function() {
            const $item = $(this);
            const keywords = $item.data('keywords') || '';
            const subject = $item.data('subject') || '';
            const difficulty = $item.data('difficulty') || '';
            
            let show = true;
            
            // Check search term
            if (searchTerm && !keywords.includes(searchTerm)) {
                show = false;
            }
            
            // Check subject filter
            if (selectedSubject && subject !== selectedSubject) {
                show = false;
            }
            
            // Check difficulty filter
            if (selectedDifficulty && difficulty !== selectedDifficulty) {
                show = false;
            }
            
            if (show) {
                $item.show();
                visibleCount++;
            } else {
                $item.hide();
                // Close accordion if hiding
                $item.find('.accordion-collapse').removeClass('show');
            }
        });
        
        // Show/hide subject sections based on visible items
        $('.subject-section').each(function() {
            const $section = $(this);
            const visibleItems = $section.find('.faq-item:visible').length;
            
            if (visibleItems > 0) {
                $section.show();
            } else {
                $section.hide();
            }
        });
        
        // Show no results message
        if (visibleCount === 0) {
            $('#noResults').show();
        } else {
            $('#noResults').hide();
        }
    }
    
    // Highlight search terms
    function highlightSearchTerms() {
        const searchTerm = $('#searchQuestions').val();
        if (searchTerm.length > 2) {
            $('.accordion-body').each(function() {
                const text = $(this).html();
                const highlightedText = text.replace(
                    new RegExp(searchTerm, 'gi'),
                    '<mark>$&</mark>'
                );
                $(this).html(highlightedText);
            });
        }
    }
    
    // Clear all filters
    window.clearAllFilters = function() {
        $('#searchQuestions').val('');
        $('#filterSubject').val('');
        $('#filterDifficulty').val('');
        $('.faq-item').show();
        $('.subject-section').show();
        $('#noResults').hide();
    };
    
    // Auto-expand first item in each section
    $('.accordion').each(function() {
        $(this).find('.accordion-item:first .accordion-collapse').addClass('show');
        $(this).find('.accordion-item:first .accordion-button').removeClass('collapsed');
    });
    
    // Track question views (analytics)
    $('.accordion-button').on('click', function() {
        const questionId = $(this).attr('data-bs-target').replace('#question', '').replace('#generalFaq', '');
        if (questionId) {
            // Send analytics data (if needed)
            console.log('Question viewed:', questionId);
        }
    });
});
</script>

<style>
.bg-gradient-primary {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}

.faq-item {
    transition: all 0.3s ease;
}

.faq-item:hover {
    transform: translateY(-2px);
}

.accordion-button:not(.collapsed) {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.accordion-button:focus {
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

mark {
    background-color: #fff3cd;
    padding: 1px 2px;
    border-radius: 2px;
}

.badge {
    font-size: 0.75em;
}
</style>
@endpush 