<?php

namespace App\Http\Controllers;

use App\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest;
use App\Services\PayrollAuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = LeaveRequest::with('employee')->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->paginate(20)->withQueryString();

        return view('leave-requests.index', compact('requests'));
    }

    public function approve(LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->status !== LeaveRequestStatus::Pending) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $leaveRequest->update([
            'status' => LeaveRequestStatus::Approved,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        $employee = $leaveRequest->employee;
        $employee->decrement('pto_balance', min($employee->pto_balance, $leaveRequest->days_requested));

        PayrollAuditLogger::log('leave_request.approved', $leaveRequest);

        return back()->with('success', 'Leave request approved.');
    }

    public function reject(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        if ($leaveRequest->status !== LeaveRequestStatus::Pending) {
            return back()->with('error', 'This request has already been reviewed.');
        }

        $leaveRequest->update([
            'status' => LeaveRequestStatus::Rejected,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
            'review_notes' => $request->input('review_notes'),
        ]);

        PayrollAuditLogger::log('leave_request.rejected', $leaveRequest);

        return back()->with('success', 'Leave request rejected.');
    }
}
