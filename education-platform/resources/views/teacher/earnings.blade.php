@extends('layouts.dashboard')

@section('title', 'Earnings & Reports')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">Earnings & Reports</h1>
        <div>
            <a href="{{ route('teacher.earnings.download') }}" class="btn btn-outline-primary">
                <i class="bi bi-download me-1"></i>Download Report
            </a>
        </div>
    </div>

    <!-- Earnings Overview Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">This Month</h6>
                            <h3 class="mb-0 text-primary">₹{{ number_format($earnings['this_month']) }}</h3>
                        </div>
                        <div class="text-primary">
                            <i class="bi bi-currency-rupee" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Last Month</h6>
                            <h3 class="mb-0 text-success">₹{{ number_format($earnings['last_month']) }}</h3>
                        </div>
                        <div class="text-success">
                            <i class="bi bi-graph-up" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Total Earnings</h6>
                            <h3 class="mb-0 text-warning">₹{{ number_format($earnings['total']) }}</h3>
                        </div>
                        <div class="text-warning">
                            <i class="bi bi-piggy-bank" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title text-muted mb-1">Average per Session</h6>
                            <h3 class="mb-0 text-info">₹{{ number_format($earnings['total'] > 0 ? $earnings['total'] / 10 : 0) }}</h3>
                        </div>
                        <div class="text-info">
                            <i class="bi bi-calculator" style="font-size: 2rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Chart -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Monthly Earnings Trend</h5>
                </div>
                <div class="card-body">
                    <canvas id="earningsChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent">
                    <h5 class="mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    @if($earnings['recent_transactions']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Student</th>
                                        <th>Subject</th>
                                        <th>Session Duration</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($earnings['recent_transactions'] as $transaction)
                                        <tr>
                                            <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                            <td>{{ $transaction->student->name }}</td>
                                            <td>{{ $transaction->subject->name }}</td>
                                            <td>{{ $transaction->duration }} min</td>
                                            <td class="text-success">₹{{ number_format($transaction->amount) }}</td>
                                            <td>
                                                <span class="badge bg-success">Completed</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
                            <p class="text-muted mt-2">No transactions yet. Start teaching to see your earnings!</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('earningsChart').getContext('2d');
    
    const earningsData = @json($earnings['monthly_trend']);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: earningsData.map(item => item.month),
            datasets: [{
                label: 'Monthly Earnings',
                data: earningsData.map(item => item.amount),
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₹' + value.toLocaleString();
                        }
                    }
                }
            }
        }
    });
});
</script>
@endpush
@endsection 