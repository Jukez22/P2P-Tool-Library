<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Librarian Dashboard – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    .sidebar { min-height: calc(100vh - 56px); }
    .panel { display:none; }
    .panel.active { display:block; }
    .sidebar .btn { font-size:0.82rem; text-align:left; border-radius:0; }
    .border-secondary-subtle { border-color: #dee2e6 !important; }
  </style>
</head>
<body class="bg-light">

<nav class="navbar navbar-light bg-white border-bottom">
  <div class="container-fluid px-3">
    <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">3EDTAK - Librarian Dashboard</a>
    <div class="d-flex align-items-center gap-2">
      <small class="text-secondary">Welcome Back, {{ $user->name ?? 'Librarian' }}!</small>
      <span class="badge bg-danger">3 alerts</span>
      <form action="{{ route('logout') }}" method="POST" class="d-inline">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

    <div class="col-md-2 bg-white border-end sidebar p-0">
      <div class="p-3 border-bottom">
        <div class="fw-bold small text-dark">{{ $user->name ?? 'Librarian' }}</div>
        <div class="text-secondary" style="font-size:0.75rem;">Librarian · Zone: {{ $user->address ?? 'October' }}</div>
      </div>
      <div class="p-1">
        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Overview</div>
        <button class="btn btn-light w-100 text-dark" onclick="show('dashboard',this)">🏠 Dashboard</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('activity',this)">📊 Activity Monitor</button>

        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Tools</div>
        <button class="btn btn-light w-100 text-secondary" onclick="show('audit',this)">🔍 Inventory Audit</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('pending',this)">⏳ Pending Approvals</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('qr',this)">📱 QR Handover</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('taxonomy',this)">🗂️ Taxonomy & Categories</button>

        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Operations</div>
        <button class="btn btn-light w-100 text-secondary" onclick="show('disputes',this)">⚖️ Dispute Mediation</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('late',this)">⚠️ Late Returns</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('blacklist',this)">🚫 Blacklist Manager</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('insurance',this)">🛡️ Insurance Claims</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('refund',this)">↩️ Refund & Credit</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('assignment',this)">👥 Librarian Assignment</button>

        <div class="text-secondary px-2 mt-2 mb-1" style="font-size:0.68rem;text-transform:uppercase;">Finance & Admin</div>
        <button class="btn btn-light w-100 text-secondary" onclick="show('revenue',this)">💵 Revenue Reports</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('promotions',this)">🎟️ Promotions</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('zones',this)">🗺️ Zone Management</button>
        <button class="btn btn-light w-100 text-secondary" onclick="show('broadcast',this)">📢 Broadcast</button>
        <form action="{{ route('logout') }}" method="POST" class="w-100 m-0">
          @csrf
          <button type="submit" class="btn btn-light w-100 text-secondary text-start">🚪 Log Out</button>
        </form>
      </div>
    </div>

    <div class="col-md-10 p-4">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show small" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show small" role="alert">
          <ul class="mb-0">
            @foreach($errors->all() as $err)
              <li>{{ $err }}</li>
            @endforeach
          </ul>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="panel active" id="panel-dashboard">
        <div class="fw-bold fs-5 mb-1">Librarian Dashboard</div>
        <div class="text-secondary small mb-3">Platform overview – Giza October Zone</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $activeRentalsCount }}</div><small class="text-secondary">Active Rentals</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-danger">{{ $openDisputesCount }}</div><small class="text-secondary">Open Disputes</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-warning">{{ $lateReturnsCount }}</div><small class="text-secondary">Late Returns</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $pendingApprovalsCount }}</div><small class="text-secondary">Pending Approvals</small></div></div>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card bg-white border">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Recent Disputes</span>
                <button class="btn btn-outline-secondary btn-sm" onclick="show('disputes',null)">View All</button>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Case</th><th>Tool</th><th>Status</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($recentDisputes as $dispute)
                    <tr>
                      <td>#{{ $dispute->id }}</td>
                      <td>{{ $dispute->reservation->tool->title ?? 'Unknown Tool' }}</td>
                      <td>
                        @if($dispute->dispute_status === 'pending')
                          <span class="badge bg-warning text-dark">Open</span>
                        @elseif($dispute->dispute_status === 'under_review')
                          <span class="badge bg-primary">Under Review</span>
                        @else
                          <span class="badge bg-success">Resolved</span>
                        @endif
                      </td>
                      <td>
                        @if($dispute->dispute_status === 'pending' || $dispute->dispute_status === 'under_review')
                          <button class="btn btn-primary btn-sm" onclick="show('disputes',null)">Review</button>
                        @else
                          <button class="btn btn-outline-secondary btn-sm">View</button>
                        @endif
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-secondary py-3">No recent disputes</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card bg-white border">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Late Returns</span>
                <button class="btn btn-outline-secondary btn-sm" onclick="show('late',null)">View All</button>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Member</th><th>Tool</th><th>Days Late</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($activeLateReturns as $late)
                    <tr>
                      <td>{{ $late->reservation->borrower->name ?? 'Unknown' }}</td>
                      <td>{{ $late->reservation->tool->title ?? 'Unknown Tool' }}</td>
                      <td>
                        @php
                          $daysLate = \Carbon\Carbon::parse($late->reservation->end_datetime)->diffInDays(now());
                        @endphp
                        <span class="badge {{ $daysLate > 2 ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $daysLate }} days</span>
                      </td>
                      <td>
                        @if($late->escalation_level === 'final_notice')
                          <button class="btn btn-danger btn-sm" onclick="show('late',null)">Suspend</button>
                        @elseif($late->escalation_level === 'penalty_level_2' || $late->escalation_level === 'penalty_level_1')
                          <button class="btn btn-danger btn-sm" onclick="show('late',null)">Escalate</button>
                        @else
                          <button class="btn btn-outline-secondary btn-sm" onclick="show('late',null)">Notify</button>
                        @endif
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-secondary py-3">No late returns</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-12">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Pending Tool Approvals</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Tool</th><th>Submitted By</th><th>Date</th><th>Category</th><th>Actions</th></tr></thead>
                  <tbody>
                    @forelse($pendingTools as $tool)
                    <tr>
                      <td>{{ $tool->title }}</td>
                      <td>{{ $tool->owner->name ?? 'Unknown' }}</td>
                      <td>{{ $tool->created_at->format('M j') }}</td>
                      <td>{{ $tool->category->name ?? 'Uncategorized' }}</td>
                      <td>
                        <button class="btn btn-success btn-sm me-1" onclick="show('pending',null)">Approve</button>
                        <button class="btn btn-danger btn-sm" onclick="show('pending',null)">Reject</button>
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-secondary py-3">No pending approvals</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-activity">
        <div class="fw-bold fs-5 mb-1">Activity Monitor</div>
        <div class="text-secondary small mb-3">Real-time overview of active rentals and pending returns</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $activeRentalsCount }}</div><small class="text-secondary">Active Rentals</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $returnsDueToday }}</div><small class="text-secondary">Returns Due Today</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $pickupsToday }}</div><small class="text-secondary">Pickups Today</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-4 fw-bold text-danger">{{ $lateReturnsCount }}</div><small class="text-secondary">Overdue</small></div></div>
        </div>
        <div class="card bg-white border">
          <div class="card-header bg-white fw-bold small">Live Rental Feed</div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Time</th><th>Status</th><th>Member</th><th>Tool</th><th>Total</th></tr></thead>
              <tbody>
                @forelse($recentReservations as $res)
                <tr>
                  <td>{{ $res->created_at->format('h:i A') }}</td>
                  <td>
                    <span class="badge bg-{{ $res->status === 'Active' ? 'success' : ($res->status === 'Pending' ? 'primary' : 'secondary') }}">{{ $res->status }}</span>
                  </td>
                  <td>{{ $res->borrower->name ?? 'Unknown' }}</td>
                  <td>{{ $res->tool->title ?? 'Unknown Tool' }}</td>
                  <td>{{ $res->total_price }} EGP</td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-secondary py-3">No live rental events</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-audit">
        <div class="fw-bold fs-5 mb-1">Inventory Audit</div>
        <div class="text-secondary small mb-3">System-guided random checks to verify lenders possess their listed tools</div>
        <div class="card bg-white border">
          <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <span class="fw-bold small">Audit Queue</span>
            <form action="{{ route('librarian.audits.dashboard-generate') }}" method="POST" class="m-0">
              @csrf
              <button type="submit" class="btn btn-primary btn-sm">Generate Random Audits</button>
            </form>
          </div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Lender</th><th>Assigned Date</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                @forelse($auditQueue as $audit)
                <tr>
                  <td>{{ $audit->lender->name ?? 'Unknown' }}</td>
                  <td>{{ $audit->assigned_at ? $audit->assigned_at->format('M j, Y') : 'N/A' }}</td>
                  <td>
                    <span class="badge bg-{{ $audit->audit_status === 'approved' ? 'success' : ($audit->audit_status === 'pending' ? 'warning text-dark' : 'danger') }}">{{ $audit->audit_status }}</span>
                  </td>
                  <td>
                    <button class="btn btn-outline-secondary btn-sm">View Details</button>
                  </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-secondary py-3">No inventory audits in queue</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-pending">
        <div class="fw-bold fs-5 mb-1">Pending Tool Approvals</div>
        <div class="text-secondary small mb-3">Review and approve new tool listings before they go live</div>
        <div class="card bg-white border">
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Tool</th><th>Lender</th><th>Category</th><th>Daily Rate</th><th>Deposit</th><th>Submitted</th><th>Actions</th></tr></thead>
              <tbody>
                @forelse($pendingTools as $tool)
                <tr>
                  <td>{{ $tool->title }}</td>
                  <td>{{ $tool->owner->name ?? 'Unknown' }}</td>
                  <td>{{ $tool->category->name ?? 'Uncategorized' }}</td>
                  <td>{{ $tool->price }} EGP</td>
                  <td>{{ $tool->deposit_price }} EGP</td>
                  <td>{{ $tool->created_at->format('M j') }}</td>
                  <td class="d-flex">
                    <form action="{{ route('librarian.tools.review', $tool->id) }}" method="POST" class="m-0 me-1">
                      @csrf
                      <input type="hidden" name="action" value="approve"/>
                      <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                    <form action="{{ route('librarian.tools.review', $tool->id) }}" method="POST" class="m-0">
                      @csrf
                      <input type="hidden" name="action" value="reject"/>
                      <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-secondary py-3">No pending tool approvals</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-qr">
        <div class="fw-bold fs-5 mb-1">QR Handover Verification</div>
        <div class="text-secondary small mb-3">Generate and scan unique QR codes to confirm physical tool transfers</div>
        <div class="row g-3">
          <div class="col-md-5">
            <div class="card bg-white border mb-3">
              <div class="card-header bg-white fw-bold small">Generate QR Code</div>
              <div class="card-body">
                <form action="{{ route('librarian.handovers.generate') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label text-dark">Select Reservation</label>
                    <select name="reservation_id" class="form-select" required>
                      @foreach($allReservations as $res)
                        <option value="RES-{{ $res->id }}">RES-{{ $res->id }} : {{ $res->tool->title ?? 'Tool' }} (Borrower: {{ $res->borrower->name ?? 'User' }})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Transfer Type</label>
                    <select name="transfer_type" class="form-select"><option value="Pickup">Pickup (Lender → Borrower)</option><option value="Return">Return (Borrower → Lender)</option></select>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Generate QR Code</button>
                </form>
                @if(session('qr_generated'))
                  @php $qrData = session('qr_generated'); @endphp
                  <div id="qrbox" class="text-center mt-3 p-3 bg-light border rounded">
                    <div style="font-size:2.2rem; color:#000; font-family:monospace;" class="fw-bold">{{ $qrData['code'] }}</div>
                    <div class="text-dark small mt-1 fw-bold">{{ $qrData['reservation_id'] }} ({{ $qrData['type'] }})</div>
                    <div class="text-success small fw-bold mt-1">✓ Ready for Verification</div>
                  </div>
                @endif
              </div>
            </div>

            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Verify / Scan QR Code</div>
              <div class="card-body">
                <form action="{{ route('librarian.handovers.verify') }}" method="POST">
                  @csrf
                  <div class="mb-3"><label class="form-label text-dark">Enter Scanned Code</label><input type="text" name="qr_code" class="form-control" placeholder="e.g. QR-A1B2C3D4" required/></div>
                  <button type="submit" class="btn btn-success w-100">Confirm Physical Handover</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Recent Handovers</div>
              <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Reservation</th><th>Tool</th><th>QR Code</th><th>Verified Time</th><th>Status</th></tr></thead>
              <tbody>
                @forelse($recentHandovers as $handover)
                <tr>
                  <td>RES-#{{ $handover->borrow_id }}</td>
                  <td>{{ $handover->reservation->tool->title ?? 'Unknown Tool' }}</td>
                  <td><code class="text-secondary">{{ $handover->qr_code }}</code></td>
                  <td>{{ $handover->verified_at ? $handover->verified_at->format('M j, h:i A') : 'N/A' }}</td>
                  <td>
                    <span class="badge bg-{{ $handover->is_verified ? 'success' : 'warning text-dark' }}">{{ $handover->is_verified ? 'Confirmed' : 'Pending' }}</span>
                  </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center text-secondary py-3">No recent handover records</td></tr>
                @endforelse
              </tbody>
            </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-taxonomy">
        <div class="fw-bold fs-5 mb-1">Taxonomy & Category Mapping</div>
        <div class="text-secondary small mb-3">Manage the hierarchical structure of tool types for accurate search results</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Add Category</span>
              </div>
              <div class="card-body">
                <form action="{{ route('librarian.categories.store') }}" method="POST">
                  @csrf
                  <div class="mb-3"><label class="form-label text-dark">Category Name</label><input type="text" name="name" class="form-control" placeholder="e.g. Power Tools" required/></div>
                  <div class="mb-3"><label class="form-label text-dark">Parent Category</label>
                    <select name="parent_id" class="form-select">
                      <option value="">-- None (Top Level) --</option>
                      @foreach($categoryTree as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                  <button type="submit" class="btn btn-primary w-100">Add Category</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Category Tree</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Category</th><th>Parent</th><th>Tools Count</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($categoryTree as $cat)
                      <tr>
                        <td class="fw-bold">{{ $cat->icon ?? '📁' }} {{ $cat->name }}</td>
                        <td>—</td>
                        <td>{{ $cat->tools()->count() ?? 0 }}</td>
                        <td><button class="btn btn-outline-secondary btn-sm">Edit</button></td>
                      </tr>
                      @foreach($cat->children as $child)
                        <tr>
                          <td>&nbsp;&nbsp;&nbsp;&nbsp;↳ {{ $child->icon ?? '📄' }} {{ $child->name }}</td>
                          <td class="text-secondary">{{ $cat->name }}</td>
                          <td>{{ $child->tools()->count() ?? 0 }}</td>
                          <td><button class="btn btn-outline-secondary btn-sm">Edit</button></td>
                        </tr>
                      @endforeach
                    @empty
                      <tr><td colspan="4" class="text-center text-secondary py-3">No tool categories created yet</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-disputes">
        <div class="fw-bold fs-5 mb-1">Dispute Mediation</div>
        <div class="text-secondary small mb-3">Review evidence and decide on deposit forfeitures</div>
        @if($allDisputes->isNotEmpty())
          @php $activeCase = $allDisputes->first(); @endphp
          <div class="card bg-white border mb-3">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
              <span class="fw-bold small text-dark">Case #{{ $activeCase->id }} – {{ $activeCase->reservation->tool->title ?? 'Tool' }}</span>
              <span class="badge bg-{{ $activeCase->dispute_status === 'resolved' ? 'success' : 'warning text-dark' }}">{{ ucfirst($activeCase->dispute_status) }}</span>
            </div>
            <div class="card-body small">
              <div class="text-secondary mb-2">Opened {{ $activeCase->created_at->format('M j, Y') }} · Borrower: {{ $activeCase->borrower->name ?? 'Unknown' }} · Lender: {{ $activeCase->lender->name ?? 'Unknown' }} · Deposit: {{ $activeCase->reservation->tool->deposit_price ?? 0 }} EGP</div>
              <div class="row g-3 mb-3">
                <div class="col-md-12">
                  <div class="card bg-light border-warning p-2">
                    <div class="text-warning small fw-bold mb-1">DISPUTE REASON / CLAIM</div>
                    <div class="small text-dark">"{{ $activeCase->dispute_reason ?? 'No details provided.' }}"</div>
                  </div>
                </div>
              </div>
              <form action="{{ route('librarian.disputes.dashboard-resolve', $activeCase->id) }}" method="POST">
                @csrf
                <div class="mb-2"><label class="form-label text-dark">Librarian Decision Notes</label><textarea name="decision_notes" class="form-control form-control-sm" rows="2" placeholder="Write your decision rationale..." required></textarea></div>
                <div class="d-flex gap-2 flex-wrap">
                  <button type="submit" name="decision_action" value="release_lender" class="btn btn-danger btn-sm">Release Deposit to Lender</button>
                  <button type="submit" name="decision_action" value="refund_borrower" class="btn btn-success btn-sm">Refund Deposit to Borrower</button>
                  <button type="submit" name="decision_action" value="split" class="btn btn-warning btn-sm text-dark">Split 50/50</button>
                </div>
              </form>
            </div>
          </div>
        @endif
        <div class="card bg-white border">
          <div class="card-header bg-white fw-bold small">All Cases</div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Case</th><th>Tool</th><th>Borrower</th><th>Lender</th><th>Deposit</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                @forelse($allDisputes as $case)
                <tr>
                  <td>#{{ $case->id }}</td>
                  <td>{{ $case->reservation->tool->title ?? 'Unknown' }}</td>
                  <td>{{ $case->borrower->name ?? 'Unknown' }}</td>
                  <td>{{ $case->lender->name ?? 'Unknown' }}</td>
                  <td>{{ $case->reservation->tool->deposit_price ?? 0 }} EGP</td>
                  <td><span class="badge bg-{{ $case->dispute_status === 'resolved' ? 'success' : 'warning text-dark' }}">{{ ucfirst($case->dispute_status) }}</span></td>
                  <td><button class="btn btn-outline-secondary btn-sm">Review</button></td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-secondary py-3">No dispute mediation cases filed</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-late">
        <div class="fw-bold fs-5 mb-1">Late Return Escalation</div>
        <div class="text-secondary small mb-3">Multi-stage notification system with increasing penalty tiers</div>
        <div class="card bg-white border">
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Member</th><th>Tool</th><th>Due Date</th><th>Days Late</th><th>Penalty</th><th>Stage</th><th>Action</th></tr></thead>
              <tbody>
                @forelse($allLateReturns as $late)
                <tr>
                  <td>{{ $late->reservation->borrower->name ?? 'Unknown' }}</td>
                  <td>{{ $late->reservation->tool->title ?? 'Unknown Tool' }}</td>
                  <td>{{ $late->reservation->end_datetime ? $late->reservation->end_datetime->format('M j') : 'N/A' }}</td>
                  <td><span class="badge bg-danger">{{ $late->days_late }} days</span></td>
                  <td class="text-primary fw-bold">{{ $late->penalty_amount }} EGP</td>
                  <td><span class="badge bg-{{ $late->escalation_level === 'final_notice' ? 'danger' : 'warning text-dark' }}">{{ ucfirst(str_replace('_', ' ', $late->escalation_level)) }}</span></td>
                  <td>
                    <form action="{{ route('librarian.late-returns.escalate', $late->id) }}" method="POST" class="d-inline">
                      @csrf
                      <button type="submit" class="btn btn-outline-danger btn-sm">Escalate</button>
                    </form>
                  </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center text-secondary py-3">No active late return escalations</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-blacklist">
        <div class="fw-bold fs-5 mb-1">Blacklist Manager</div>
        <div class="text-secondary small mb-3">Temporarily or permanently restrict users based on policy violations</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Add Restriction</div>
              <div class="card-body">
                <form action="{{ route('librarian.restrictions.apply') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label text-dark">Select Member</label>
                    <select name="member_id" class="form-select" required>
                      @foreach($allUsers as $usr)
                        <option value="{{ $usr->id }}">{{ $usr->name }} ({{ $usr->email }})</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Restriction Type</label>
                    <select name="type" class="form-select"><option value="Temporary Suspension (30 days)">Temporary Suspension (30 days)</option><option value="Temporary Suspension (60 days)">Temporary Suspension (60 days)</option><option value="Permanent Ban">Permanent Ban</option></select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Reason</label><textarea name="reason" class="form-control" rows="2" placeholder="Reason for restriction..." required></textarea></div>
                  <button type="submit" class="btn btn-danger w-100">Apply Restriction</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Restricted Members</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Member</th><th>Reason</th><th>Since</th><th>Type</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($restrictedMembers as $sus)
                    <tr>
                      <td>{{ $sus->user->name ?? 'Unknown User' }}</td>
                      <td>{{ $sus->reason }}</td>
                      <td>{{ $sus->created_at->format('M j, Y') }}</td>
                      <td>
                        <span class="badge bg-{{ $sus->type === 'permanent_ban' ? 'danger' : 'warning text-dark' }}">{{ ucfirst(str_replace('_', ' ', $sus->type)) }}</span>
                      </td>
                      <td>
                        <form action="{{ route('librarian.restrictions.lift', $sus->id) }}" method="POST" class="d-inline">
                          @csrf
                          <button type="submit" class="btn btn-outline-success btn-sm">Lift Ban</button>
                        </form>
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-secondary py-3">No restricted members on blacklist</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-insurance">
        <div class="fw-bold fs-5 mb-1">Insurance Claims</div>
        <div class="text-secondary small mb-3">Automated claim reports for high-value tool theft or total destruction</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">New Claim</div>
              <div class="card-body">
                <form action="{{ route('librarian.insurance-claims.dashboard-store') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label text-dark">Select Reservation</label>
                    <select name="reservation_id" class="form-select" required>
                      @foreach($allReservations as $res)
                        <option value="RES-{{ $res->id }}">RES-{{ $res->id }} : {{ $res->tool->title ?? 'Tool' }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Claim Type</label>
                    <select name="claim_type" class="form-select"><option value="Total Damage">Total Damage</option><option value="Theft">Theft</option><option value="Partial Damage">Partial Damage</option></select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Estimated Value (EGP)</label><input type="number" name="estimated_loss" class="form-control" placeholder="0" required/></div>
                  <div class="mb-3"><label class="form-label text-dark">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
                  <button type="submit" class="btn btn-primary w-100">Submit Claim</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Open Claims</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Claim #</th><th>Tool</th><th>Value</th><th>Type</th><th>Submitted</th><th>Status</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($openClaims as $claim)
                    <tr>
                      <td>CLM-#{{ $claim->id }}</td>
                      <td>{{ $claim->tool->title ?? 'Unknown Tool' }}</td>
                      <td>{{ $claim->estimated_loss }} EGP</td>
                      <td>{{ ucfirst(str_replace('_', ' ', $claim->claim_type)) }}</td>
                      <td>{{ $claim->created_at->format('M j') }}</td>
                      <td><span class="badge bg-{{ $claim->claim_status === 'approved' ? 'success' : 'warning text-dark' }}">{{ ucfirst($claim->claim_status) }}</span></td>
                      <td><button class="btn btn-outline-secondary btn-sm">View</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center text-secondary py-3">No active insurance claims</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-refund">
        <div class="fw-bold fs-5 mb-1">Refund & Credit Reconciliation</div>
        <div class="text-secondary small mb-3">Handle partial refunds if a tool breaks mid-use through no fault of the borrower</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Process Refund</div>
              <div class="card-body">
                <form action="{{ route('librarian.refunds.process') }}" method="POST">
                  @csrf
                  <div class="mb-3">
                    <label class="form-label text-dark">Select Reservation</label>
                    <select name="reservation_id" class="form-select" required>
                      @foreach($allReservations as $res)
                        <option value="RES-{{ $res->id }}">RES-{{ $res->id }} : {{ $res->tool->title ?? 'Tool' }}</option>
                      @endforeach
                    </select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Refund Type</label>
                    <select name="refund_type" class="form-select"><option value="Full Refund">Full Refund</option><option value="Partial Refund">Partial Refund</option><option value="Platform Credit">Platform Credit</option></select>
                  </div>
                  <div class="mb-3"><label class="form-label text-dark">Amount (EGP)</label><input type="number" name="amount" class="form-control" placeholder="0" required/></div>
                  <div class="mb-3"><label class="form-label text-dark">Reason</label><textarea name="reason" class="form-control" rows="2" placeholder="e.g. Tool broke mid-use due to manufacturing defect"></textarea></div>
                  <button type="submit" class="btn btn-primary w-100">Process Refund</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Recent Refunds</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Reservation</th><th>Member</th><th>Amount</th><th>Method / Reason</th><th>Status</th></tr></thead>
                  <tbody>
                    @forelse($recentRefunds as $ref)
                    <tr>
                      <td>RES-#{{ $ref->reservation_id }}</td>
                      <td>{{ $ref->reservation->borrower->name ?? 'Unknown' }}</td>
                      <td class="text-primary fw-bold">{{ abs($ref->amount) }} EGP</td>
                      <td>{{ ucfirst($ref->payment_method) }} (Reconciled)</td>
                      <td><span class="badge bg-success">{{ ucfirst($ref->status) }}</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-secondary py-3">No recent refund reconciliations processed</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-assignment">
        <div class="fw-bold fs-5 mb-1">Librarian Assignment</div>
        <div class="text-secondary small mb-3">Distribute pending approvals and disputes among available staff</div>
        <div class="row g-3">
          <div class="col-md-5">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Staff Availability</div>
              <div class="card-body p-0">
                <table class="table small mb-0">
                  <thead><tr><th>Librarian</th><th>Zone</th><th>Active Tasks</th><th>Status</th></tr></thead>
                  <tbody>
                    @forelse($staffMembers as $staff)
                    <tr>
                      <td>{{ $staff->name }}</td>
                      <td>{{ $staff->address ?? 'General' }}</td>
                      <td>{{ \App\Models\Dispute::where('librarian_id', $staff->id)->where('dispute_status', '!=', 'resolved')->count() }}</td>
                      <td><span class="badge bg-success">Available</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center text-secondary py-3">No active library staff found</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Unassigned Tasks</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Task</th><th>Type</th><th>Priority</th><th>Assign To</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($allDisputes->whereNull('librarian_id') as $task)
                    <tr>
                      <td>Dispute #{{ $task->id }}</td>
                      <td>Dispute</td>
                      <td><span class="badge bg-danger">High</span></td>
                      <td>
                        <form action="{{ route('librarian.disputes.dashboard-assign', $task->id) }}" method="POST" class="d-flex gap-1" id="form-assign-{{ $task->id }}">
                          @csrf
                          <select name="librarian_id" class="form-select form-select-sm" required>
                            @foreach($staffMembers as $staff)
                              <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                            @endforeach
                          </select>
                        </form>
                      </td>
                      <td><button type="submit" form="form-assign-{{ $task->id }}" class="btn btn-primary btn-sm">Assign</button></td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center text-secondary py-3">No unassigned tasks pending</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-revenue">
        <div class="fw-bold fs-5 mb-1">Revenue Reports</div>
        <div class="text-secondary small mb-3">Platform fees, insurance cuts, and lender earnings</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">{{ number_format($totalRevenue, 0) }} EGP</div><small class="text-secondary">Gross Revenue</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">{{ number_format($platformFees, 0) }} EGP</div><small class="text-secondary">Platform Fees (5%)</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">{{ number_format($lenderPayouts, 0) }} EGP</div><small class="text-secondary">Lender Payouts</small></div></div>
          <div class="col-md-3"><div class="card bg-white border p-3 text-center"><div class="fs-5 fw-bold text-primary">{{ number_format($depositBalance, 0) }} EGP</div><small class="text-secondary">Deposit Balance</small></div></div>
        </div>
        <div class="card bg-white border">
          <div class="card-header bg-white fw-bold small">Monthly Breakdown</div>
          <div class="card-body p-0">
            <table class="table table-hover small mb-0">
              <thead><tr><th>Month</th><th>Rentals</th><th>Gross Revenue</th><th>Platform Fee</th><th>Lender Payouts</th><th>Insurance Cuts</th></tr></thead>
              <tbody>
                @forelse($monthlyBreakdown as $mb)
                  <tr>
                    <td class="fw-bold">{{ $mb->month_label }}</td>
                    <td>{{ $mb->rentals }}</td>
                    <td>{{ number_format($mb->gross, 0) }} EGP</td>
                    <td>{{ number_format($mb->gross * 0.05, 0) }} EGP</td>
                    <td>{{ number_format($mb->gross * 0.90, 0) }} EGP</td>
                    <td>{{ number_format($mb->gross * 0.05, 0) }} EGP</td>
                  </tr>
                @empty
                  <tr><td>May 2026</td><td>84</td><td>12,450 EGP</td><td>623 EGP</td><td>9,120 EGP</td><td>310 EGP</td></tr>
                  <tr><td>Apr 2026</td><td>71</td><td>10,380 EGP</td><td>519 EGP</td><td>7,650 EGP</td><td>260 EGP</td></tr>
                  <tr><td>Mar 2026</td><td>65</td><td>9,100 EGP</td><td>455 EGP</td><td>6,800 EGP</td><td>225 EGP</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-promotions">
        <div class="fw-bold fs-5 mb-1">Promotional Campaigns</div>
        <div class="text-secondary small mb-3">Create time-limited discount codes for specific tool categories</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Create Campaign</div>
              <div class="card-body">
                <form action="{{ route('librarian.promotions.store') }}" method="POST">
                  @csrf
                  <div class="mb-3"><label class="form-label text-dark">Campaign Name</label><input type="text" name="name" class="form-control" placeholder="e.g. Gardening Week" required/></div>
                  <div class="mb-3"><label class="form-label text-dark">Tool Category</label><select name="category" class="form-select"><option value="All Categories">All Categories</option><option value="Power Tools">Power Tools</option><option value="Woodworking">Woodworking</option><option value="Sewing">Sewing</option></select></div>
                  <div class="row g-2 mb-3">
                    <div class="col-6"><label class="form-label text-dark">Discount %</label><input type="number" name="discount" class="form-control" placeholder="20" min="1" max="100" required/></div>
                    <div class="col-6"><label class="form-label text-dark">Code</label><input type="text" name="code" class="form-control" placeholder="GARDEN20" required/></div>
                  </div>
                  <div class="row g-2 mb-3">
                    <div class="col-6"><label class="form-label text-dark">Start</label><input type="date" name="start_date" class="form-control"/></div>
                    <div class="col-6"><label class="form-label text-dark">End</label><input type="date" name="end_date" class="form-control"/></div>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Launch Campaign</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Active Campaigns</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Campaign</th><th>Code</th><th>Discount</th><th>Category</th><th>Expires</th><th>Status</th><th>Action</th></tr></thead>
                  <tbody>
                    @forelse($campaigns as $camp)
                      <tr>
                        <td>{{ $camp->name }}</td>
                        <td><strong class="text-primary">{{ $camp->code }}</strong></td>
                        <td>{{ $camp->discount }}%</td>
                        <td>{{ $camp->category }}</td>
                        <td>{{ $camp->end_date ? $camp->end_date->format('M j') : 'No Expiry' }}</td>
                        <td><span class="badge bg-success">{{ $camp->is_active ? 'Active' : 'Inactive' }}</span></td>
                        <td><button class="btn btn-outline-secondary btn-sm">Edit</button></td>
                      </tr>
                    @empty
                      @if(session('new_campaign'))
                        @php $nc = session('new_campaign'); @endphp
                        <tr><td>{{ $nc['name'] }}</td><td><strong class="text-primary">{{ $nc['code'] }}</strong></td><td>{{ $nc['discount'] }}</td><td>{{ $nc['category'] }}</td><td>{{ $nc['expires'] }}</td><td><span class="badge bg-success">Active</span></td><td><button class="btn btn-outline-secondary btn-sm">Edit</button></td></tr>
                      @else
                        <tr><td colspan="7" class="text-center text-secondary py-3">No active promotional campaigns launched</td></tr>
                      @endif
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-zones">
        <div class="fw-bold fs-5 mb-1">Zone Management</div>
        <div class="text-secondary small mb-3">Define administrative boundaries for local community groups</div>
        <div class="row g-3">
          <div class="col-md-4">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">Add New Zone</div>
              <div class="card-body">
                <form action="{{ route('librarian.zones.store') }}" method="POST">
                  @csrf
                  <div class="mb-3"><label class="form-label text-dark">Zone Name</label><input type="text" name="name" class="form-control" placeholder="e.g. New Cairo" required/></div>
                  <div class="mb-3"><label class="form-label text-dark">City</label><select name="city" class="form-select"><option value="Cairo">Cairo</option><option value="Giza">Giza</option><option value="Alexandria">Alexandria</option></select></div>
                  <div class="mb-3"><label class="form-label text-dark">Assign Librarian</label>
                    <select class="form-select">
                      <option value="">Unassigned</option>
                      @foreach($staffMembers as $staff)
                        <option value="{{ $staff->id }}">{{ $staff->name }}</option>
                      @endforeach
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Create Zone</button>
                </form>
              </div>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card bg-white border">
              <div class="card-header bg-white fw-bold small">All Zones</div>
              <div class="card-body p-0">
                <table class="table table-hover small mb-0">
                  <thead><tr><th>Zone</th><th>City</th><th>Members</th><th>Tools</th><th>Librarian</th><th>Status</th></tr></thead>
                  <tbody>
                    @forelse($zones as $zone)
                    <tr>
                      <td class="fw-bold">{{ $zone->name }}</td>
                      <td>{{ $zone->city ?? 'Cairo' }}</td>
                      <td>{{ \App\Models\User::where('address', 'like', '%' . $zone->name . '%')->count() }}</td>
                      <td>{{ \App\Models\Tool::whereHas('owner', function($q) use($zone) { $q->where('address', 'like', '%' . $zone->name . '%'); })->count() }}</td>
                      <td>{{ \App\Models\User::where('address', 'like', '%' . $zone->name . '%')->where('role', 'librarian')->first()->name ?? 'Unassigned' }}</td>
                      <td><span class="badge bg-success">Active</span></td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center text-secondary py-3">No active zones configured</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-broadcast">
        <div class="fw-bold fs-5 mb-1">Broadcast Message</div>
        <div class="text-secondary small mb-3">Notify users in specific regions about delays or important updates</div>
        <div class="card bg-white border" style="max-width:520px;">
          <div class="card-body">
            <form action="{{ route('librarian.broadcasts.send') }}" method="POST">
              @csrf
              <div class="mb-3"><label class="form-label text-dark">Target Zone</label><select name="zone" class="form-select"><option value="All Zones">All Zones</option><option value="Maadi">Maadi</option><option value="Nasr City">Nasr City</option><option value="Heliopolis">Heliopolis</option><option value="Dokki">Dokki</option></select></div>
              <div class="mb-3"><label class="form-label text-dark">Target Audience</label><select name="audience" class="form-select"><option value="All Members">All Members</option><option value="Borrowers Only">Borrowers Only</option><option value="Lenders Only">Lenders Only</option></select></div>
              <div class="mb-3"><label class="form-label text-dark">Channel</label><select name="channel" class="form-select"><option value="Email + SMS">Email + SMS</option><option value="Email Only">Email Only</option><option value="SMS Only">SMS Only</option><option value="In-App Only">In-App Only</option></select></div>
              <div class="mb-3"><label class="form-label text-dark">Message</label><textarea name="message" class="form-control" rows="4" placeholder="e.g. Due to heavy rain today, pickup/dropoff in Maadi may be delayed by 1-2 hours..." required></textarea></div>
              <button type="submit" class="btn btn-primary w-100">Send Broadcast</button>
            </form>
          </div>
        </div>
      </div>

    </div></div></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function show(id, el) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.getElementById('panel-' + id).classList.add('active');
    document.querySelectorAll('.sidebar .btn').forEach(b => { b.classList.remove('text-dark'); b.classList.add('text-secondary'); });
    if (el) { el.classList.remove('text-secondary'); el.classList.add('text-dark'); }
  }
  // 3shan yeb2a active 3ala awel wa7da
  document.querySelector('.sidebar .btn').classList.add('text-dark');
  document.querySelector('.sidebar .btn').classList.remove('text-secondary');
</script>
</body>
</html>