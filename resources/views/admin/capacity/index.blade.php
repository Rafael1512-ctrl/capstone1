@extends('layouts.master')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <h1 class="h3 mb-0">Ticket Capacity Requests</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h6 class="m-0 font-weight-bold text-primary">Pending & Recent Requests</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Organizer / Event</th>
                            <th>Ticket Type</th>
                            <th>Current / Requested</th>
                            <th>Reason</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $req->event->organizer->name ?? 'System' }}</div>
                                <div class="small text-muted">{{ $req->event->title ?? 'N/A' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-secondary px-3">{{ $req->ticketType->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="small text-muted">Cur: <span class="fw-bold text-dark">{{ $req->current_capacity }}</span></div>
                                <div class="fw-bold text-primary">Req: {{ $req->requested_capacity }}</div>
                            </td>
                            <td class="small" style="max-width: 200px;">
                                {{ $req->reason }}
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-clock me-1"></i> Pending
                                    </span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-check-circle me-1"></i> Approved
                                    </span>
                                @else
                                    <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm">
                                        <i class="fas fa-times-circle me-1"></i> Rejected
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                <div class="d-flex gap-2">
                                    <form id="approve-form-{{ $req->id }}" action="{{ route('admin.capacity.approve', $req->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="button" class="btn btn-sm btn-success px-3 rounded-pill shadow-sm"
                                                onclick="confirmApprove({{ $req->id }})">
                                            Approve
                                        </button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-outline-danger px-3 rounded-pill"
                                            onclick="openRejectModal({{ $req->id }})">
                                        Reject
                                    </button>
                                </div>
                                @else
                                    <span class="text-muted small italic">{{ $req->admin_notes }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">No capacity requests found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Reject Confirmation Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Reject Request</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <p class="text-muted small">Please provide a reason why you are rejecting this request. This will be shown to the organizer.</p>
                    <textarea name="admin_notes" rows="4" class="form-control" placeholder="Ex: Capacity too high for venue space..." required></textarea>
                </div>
                <div class="modal-footer border-0 pt-0 p-4">
                    <button type="button" class="btn btn-light px-4 rounded-pill" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger px-4 rounded-pill shadow">Reject Now</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function confirmApprove(requestId) {
        swal({
            title: "Approve Capacity Change?",
            text: "This will update the ticket slots immediately. Are you sure?",
            icon: "warning",
            buttons: {
                cancel: {
                    visible: true,
                    text: "Cancel",
                    className: "btn btn-danger",
                },
                confirm: {
                    text: "Yes, Approve it!",
                    className: "btn btn-success",
                },
            },
        }).then((willApprove) => {
            if (willApprove) {
                document.getElementById('approve-form-' + requestId).submit();
            }
        });
    }

    function openRejectModal(requestId) {
        const form = document.getElementById('rejectForm');
        form.action = `/admin/capacity-requests/${requestId}/reject`;
        const modal = new bootstrap.Modal(document.getElementById('rejectModal'));
        modal.show();
    }
</script>
@endsection
