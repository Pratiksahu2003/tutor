@extends('admin.layout')

@section('title', 'Question Details')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.questions.index') }}">Questions</a></li>
    <li class="breadcrumb-item active">View Question</li>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="page-title">
                <i class="fas fa-question-circle me-2"></i>
                Question Details
            </h1>
            <div>
                <a href="{{ route('admin.questions.edit', $question) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-2"></i>Edit Question
                </a>
                <a href="{{ route('admin.questions.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to Questions
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Question Information</h5>
            </div>
            <div class="card-body">
                <h6>{{ $question->title ?? 'Untitled Question' }}</h6>
                <div class="mt-3">
                    {!! nl2br(e($question->question_text ?? 'No question text available')) !!}
                </div>
                
                @if($question->image)
                <div class="mt-3">
                    <img src="{{ Storage::url($question->image) }}" alt="Question Image" class="img-fluid" style="max-width: 500px;">
                </div>
                @endif

                @if($question->options && count($question->options) > 0)
                <div class="mt-4">
                    <h6>Options:</h6>
                    <ul class="list-unstyled">
                        @foreach($question->options as $index => $option)
                        <li class="mb-2">
                            <span class="badge bg-light text-dark me-2">{{ chr(65 + $index) }}</span>
                            {{ $option }}
                            @if($question->correct_answers && in_array($index, $question->correct_answers))
                                <span class="badge bg-success ms-2">Correct</span>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endif

                @if($question->explanation)
                <div class="mt-4">
                    <h6>Explanation:</h6>
                    <p>{{ $question->explanation }}</p>
                </div>
                @endif

                @if($question->hint)
                <div class="mt-4">
                    <h6>Hint:</h6>
                    <p>{{ $question->hint }}</p>
                </div>
                @endif

                @if($question->solution_steps)
                <div class="mt-4">
                    <h6>Solution Steps:</h6>
                    <p>{{ $question->solution_steps }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Question Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Type:</th>
                        <td><span class="badge bg-info">{{ $question->type_display ?? 'Unknown' }}</span></td>
                    </tr>
                    <tr>
                        <th>Difficulty:</th>
                        <td><span class="badge bg-{{ $question->difficulty_color ?? 'secondary' }}">{{ ucfirst($question->difficulty ?? 'Unknown') }}</span></td>
                    </tr>
                    <tr>
                        <th>Marks:</th>
                        <td>{{ $question->marks ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Time Limit:</th>
                        <td>{{ $question->time_limit ? $question->time_limit . ' seconds' : 'No limit' }}</td>
                    </tr>
                    <tr>
                        <th>Subject:</th>
                        <td>{{ $question->subject->name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Topic:</th>
                        <td>{{ $question->topic ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Class Level:</th>
                        <td>{{ ucfirst($question->class_level ?? 'Any') }}</td>
                    </tr>
                    <tr>
                        <th>Board:</th>
                        <td>{{ $question->board ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Category:</th>
                        <td>{{ ucfirst($question->category ?? 'practice') }}</td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><span class="badge bg-{{ $question->status_color ?? 'secondary' }}">{{ ucfirst($question->status ?? 'draft') }}</span></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Usage Statistics</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Usage Count:</th>
                        <td>{{ $question->usage_count ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Total Attempts:</th>
                        <td>{{ $question->total_attempts ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Correct Attempts:</th>
                        <td>{{ $question->correct_attempts ?? 0 }}</td>
                    </tr>
                    <tr>
                        <th>Success Rate:</th>
                        <td>{{ $question->success_rate ? number_format($question->success_rate, 1) . '%' : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Average Time:</th>
                        <td>{{ $question->average_time ? number_format($question->average_time, 1) . ' seconds' : 'N/A' }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Meta Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th>Created By:</th>
                        <td>{{ $question->creator->name ?? 'Unknown' }}</td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $question->created_at->format('M d, Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Last Updated:</th>
                        <td>{{ $question->updated_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @if($question->reviewer)
                    <tr>
                        <th>Reviewed By:</th>
                        <td>{{ $question->reviewer->name }}</td>
                    </tr>
                    <tr>
                        <th>Reviewed At:</th>
                        <td>{{ $question->reviewed_at->format('M d, Y H:i') }}</td>
                    </tr>
                    @endif
                    @if($question->tags && count($question->tags) > 0)
                    <tr>
                        <th>Tags:</th>
                        <td>
                            @foreach($question->tags as $tag)
                                <span class="badge bg-light text-dark me-1">{{ $tag }}</span>
                            @endforeach
                        </td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>
@endsection 