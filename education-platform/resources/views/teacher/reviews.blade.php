@extends('layouts.dashboard')

@section('title', 'Reviews')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Reviews</h1>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" style="width: auto;">
                <option>All Reviews</option>
                <option>5 Stars</option>
                <option>4 Stars</option>
                <option>3 Stars</option>
                <option>2 Stars</option>
                <option>1 Star</option>
            </select>
        </div>
    </div>

    <!-- Review Statistics -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h2 mb-1 text-warning">{{ number_format($teacherProfile->rating, 1) }}</div>
                    <div class="text-muted">Average Rating</div>
                    <div class="mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="bi bi-star{{ $i <= $teacherProfile->rating ? '-fill' : '' }} text-warning"></i>
                        @endfor
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h2 mb-1 text-primary">{{ $reviews->total() }}</div>
                    <div class="text-muted">Total Reviews</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h2 mb-1 text-success">{{ $reviews->where('rating', 5)->count() }}</div>
                    <div class="text-muted">5 Star Reviews</div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="h2 mb-1 text-info">{{ $reviews->where('teacher_reply', '!=', null)->count() }}</div>
                    <div class="text-muted">Replied To</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews List -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-transparent">
            <h5 class="mb-0">All Reviews</h5>
        </div>
        <div class="card-body">
            @if($reviews->count() > 0)
                @foreach($reviews as $review)
                    <div class="border-bottom pb-4 mb-4">
                        <div class="d-flex justify-content-between align-items-start mb-3">
                            <div class="d-flex align-items-center">
                                <img src="{{ $review->user->profile_image ? asset('storage/' . $review->user->profile_image) : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name) . '&size=50&background=random' }}" 
                                     alt="{{ $review->user->name }}" 
                                     class="rounded-circle me-3" 
                                     width="50" height="50">
                                <div>
                                    <div class="fw-bold">{{ $review->user->name }}</div>
                                    <div class="text-muted">{{ $review->created_at->format('M d, Y') }}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="text-warning mb-1">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $review->rating ? '-fill' : '' }}"></i>
                                    @endfor
                                </div>
                                <small class="text-muted">{{ $review->rating }}/5</small>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <p class="mb-0">{{ $review->comment }}</p>
                        </div>

                        @if($review->teacher_reply)
                            <div class="bg-light p-3 rounded mb-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <small class="text-muted fw-bold">Your Reply:</small>
                                        <p class="mb-0 mt-1">{{ $review->teacher_reply }}</p>
                                    </div>
                                    <small class="text-muted">{{ $review->updated_at->format('M d, Y') }}</small>
                                </div>
                            </div>
                        @endif

                        @if(!$review->teacher_reply)
                            <button class="btn btn-sm btn-outline-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#replyModal" 
                                    data-review-id="{{ $review->id }}">
                                <i class="bi bi-reply me-1"></i>Reply
                            </button>
                        @endif
                    </div>
                @endforeach

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $reviews->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-star text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No Reviews Yet</h5>
                    <p class="text-muted">You haven't received any reviews yet. Start teaching to get reviews from your students!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Reply Modal -->
<div class="modal fade" id="replyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reply to Review</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('teacher.reviews.reply', ':review_id') }}" method="POST" id="replyForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Your Reply</label>
                        <textarea class="form-control" name="reply" rows="4" placeholder="Write your reply to this review..." required></textarea>
                        <small class="text-muted">Your reply will be visible to the student and other users.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Send Reply</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle reply modal
    const replyModal = document.getElementById('replyModal');
    if (replyModal) {
        replyModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const reviewId = button.getAttribute('data-review-id');
            const form = document.getElementById('replyForm');
            const action = form.getAttribute('action').replace(':review_id', reviewId);
            form.setAttribute('action', action);
        });
    }
});
</script>
@endpush
@endsection 