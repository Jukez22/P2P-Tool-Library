<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Maintenance Dashboard – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; color: #212529; font-family: 'Inter', sans-serif; }
    .sidebar { min-height: 100vh; background-color: #ffffff; border-right: 1px solid #dee2e6; position: sticky; top: 0; }
    .panel { display:none; }
    .panel.active { display:block; }
    .sidebar .btn { font-size:0.82rem; text-align:left; border-radius:0; border: none; color: #495057; padding: 10px 20px; transition: 0.2s; }
    .sidebar .btn:hover { background-color: #f1f4f9; color: #0d6efd; }
    .sidebar .btn.active-link { background-color: #e7f1ff; color: #0d6efd; font-weight: 600; border-right: 3px solid #0d6efd; }
    .card { border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.05); border: 1px solid #eef0f2 !important; }
    .navbar { background-color: #fff !important; border-bottom: 2px solid #0d6efd; }
    .section-head { font-size: 0.65rem; text-transform: uppercase; font-weight: 800; color: #adb5bd; padding: 20px 20px 5px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top shadow-sm">
  <div class="container-fluid px-lg-5">
    <a class="navbar-brand fw-bold text-primary" href="{{ route('maintenance.dashboard') }}">🛠️ 3EDTAK Maintenance</a>
    <div class="d-flex align-items-center gap-3">
      <div class="text-end d-none d-sm-block">
        <div class="small fw-bold">{{ $user->name }}</div>
        <div class="text-muted small">Technician ID: #{{ $user->id }}</div>
      </div>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm px-3">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

    <!-- Sidebar -->
    <div class="col-md-2 sidebar p-0 d-none d-md-block shadow-sm">
      <div class="section-head">Inspections</div>
      <button class="btn w-100 active-link" onclick="show('queue',this)">📋 Priority Queue</button>
      <button class="btn w-100" onclick="show('trigger',this)">⚡ Usage Triggers</button>
      <button class="btn w-100" onclick="show('safety',this)">🛡️ Safety Certs</button>
      <button class="btn w-100" onclick="show('lockout',this)">🔒 Tool Lockout</button>
      <button class="btn w-100" onclick="show('battery',this)">🔋 Battery Health</button>

      <div class="section-head">Repairs</div>
      <button class="btn w-100" onclick="show('estimator',this)">💰 Cost Estimator</button>
      <button class="btn w-100" onclick="show('external',this)">🏭 External Repair</button>
      <button class="btn w-100" onclick="show('parts',this)">🔩 Spare Parts</button>

      <div class="section-head">Inventory & Admin</div>
      <button class="btn w-100" onclick="show('consumables',this)">📦 Consumables</button>
      <button class="btn w-100" onclick="show('warranty',this)">📅 Warranty Alerts</button>
      <button class="btn w-100" onclick="show('disposal',this)">♻️ Disposal</button>
      <button class="btn w-100" onclick="show('knowledgebase',this)">📖 Knowledge Base</button>
      <button class="btn w-100" onclick="show('metrics',this)">📊 My Metrics</button>
    </div>

    <!-- Content -->
    <div class="col-md-10 p-4">

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <!-- Panel: Queue -->
      <div class="panel active" id="panel-queue">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <div class="fw-bold fs-5">Maintenance Priority Queue</div>
            <div class="text-secondary small">Tools prioritized by rental demand and safety score</div>
          </div>
        </div>
        <div class="card overflow-hidden">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light"><tr><th>Tool</th><th>Issue/Log</th><th>Priority</th><th>Technician</th><th>Action</th></tr></thead>
            <tbody>
              @forelse($queue as $log)
              <tr>
                <td class="fw-bold">{{ $log->tool->title }}</td>
                <td>{{ $log->description ?? 'Routine maintenance check' }}</td>
                <td>
                    @php $score = rand(70, 99); @endphp
                    <span class="badge {{ $score > 90 ? 'bg-danger' : 'bg-warning text-dark' }}">{{ $score }}/100</span>
                </td>
                <td>{{ $log->technician_id ? 'Assigned' : 'Unassigned' }}</td>
                <td>
                  @if($log->status === 'scheduled')
                    <form action="{{ route('maintenance.queue.start') }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="log_id" value="{{ $log->id }}">
                      <button type="submit" class="btn btn-primary btn-sm px-3">Start Work</button>
                    </form>
                  @elseif($log->status === 'in-progress')
                    <form action="{{ route('maintenance.queue.complete') }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="log_id" value="{{ $log->id }}">
                      <input type="hidden" name="is_successful" value="1">
                      <button type="submit" class="btn btn-success btn-sm px-2">✓ Done</button>
                    </form>
                    <form action="{{ route('maintenance.queue.complete') }}" method="POST" class="d-inline">
                      @csrf
                      <input type="hidden" name="log_id" value="{{ $log->id }}">
                      <input type="hidden" name="is_successful" value="0">
                      <button type="submit" class="btn btn-outline-danger btn-sm px-2">✗ Failed</button>
                    </form>
                  @endif
                </td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted p-4">No tools in the queue. Everything is healthy!</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Trigger -->
      <div class="panel" id="panel-trigger">
        <div class="fw-bold fs-5 mb-3">Usage-Based Maintenance Triggers</div>
        <div class="row g-4">
          <div class="col-md-4">
            <div class="card p-4">
              <h6 class="fw-bold mb-3">Set New Trigger</h6>
              <form action="{{ route('maintenance.trigger.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="small fw-bold">Select Tool</label>
                    <select name="tool_id" class="form-select">
                        @foreach($toolsForTriggers as $t)
                        <option value="{{ $t->id }}">{{ $t->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Usage Threshold (Uses)</label>
                    <input type="number" name="threshold" class="form-control" placeholder="e.g. 100">
                </div>
                <button type="submit" class="btn btn-primary w-100 fw-bold">Add Rule</button>
              </form>
            </div>
          </div>
          <div class="col-md-8">
            <div class="card overflow-hidden">
              <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Tool</th><th>Current Usage</th><th>Threshold</th><th>Status</th></tr></thead>
                <tbody>
                  @foreach($toolsForTriggers->take(10) as $t)
                  <tr>
                    <td>{{ $t->title }}</td>
                    <td>{{ $t->usage_count }}</td>
                    <td>{{ $t->maintenance_interval_uses ?? '50' }}</td>
                    <td>
                      @if($t->usage_count >= ($t->maintenance_interval_uses ?? 50))
                      <span class="badge bg-danger">Flagged</span>
                      @else
                      <span class="badge bg-success">Healthy</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel: Safety -->
      <div class="panel" id="panel-safety">
        <div class="fw-bold fs-5 mb-3">Safety Certification Logger</div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Tool</th><th>Last Test</th><th>Expiry Date</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              @foreach($safetyTools as $t)
              <tr>
                <td class="fw-bold">{{ $t->title }}</td>
                <td>{{ $t->created_at ? $t->created_at->format('M Y') : 'N/A' }}</td>
                <td>{{ $t->safety_cert_expiry_date ? $t->safety_cert_expiry_date->format('M d, Y') : 'N/A' }}</td>
                <td>
                  @if($t->safety_cert_expiry_date && $t->safety_cert_expiry_date->isPast())
                  <span class="badge bg-danger">Expired</span>
                  @else
                  <span class="badge bg-success">Valid</span>
                  @endif
                </td>
                <td>
                    <form action="{{ route('maintenance.safety.renew') }}" method="POST" class="d-inline">
                        @csrf
                        <input type="hidden" name="tool_id" value="{{ $t->id }}">
                        <input type="hidden" name="safety_cert_expiry_date" value="{{ now()->addYear()->toDateString() }}">
                        <button type="submit" class="btn btn-outline-primary btn-sm">Renew Cert</button>
                    </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <div class="panel" id="panel-lockout">
        <div class="fw-bold fs-5 mb-1">Unfit for Use – Lockout System</div>
        <div class="alert alert-warning small mb-4">⚠️ Locked tools are automatically hidden from the public marketplace.</div>
        <div class="card p-4 mb-4">
          <form action="{{ route('maintenance.safety.lockout') }}" method="POST" class="row g-3 align-items-end">
            @csrf
            <div class="col-md-4">
                <label class="small fw-bold">Select Tool</label>
                <select name="tool_id" class="form-select" required>
                    @foreach($toolsForTriggers as $t)
                    <option value="{{ $t->id }}">{{ $t->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4"><label class="small fw-bold">Reason for Lockout</label><input type="text" name="reason" class="form-control" placeholder="e.g. Exposed wiring" required></div>
            <div class="col-md-4"><button type="submit" class="btn btn-danger w-100 fw-bold">Activate Lockout</button></div>
          </form>
        </div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0 text-center">
            <thead class="table-light"><tr><th>Tool</th><th>Locked Date</th><th>Condition</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              @foreach($toolsForTriggers->where('is_unfit', true) as $t)
              <tr>
                <td class="fw-bold text-danger">{{ $t->title }}</td>
                <td>{{ $t->created_at ? $t->created_at->format('M d') : 'N/A' }}</td>
                <td>{{ ucfirst($t->condition_status) }}</td>
                <td><span class="badge bg-dark">LOCKED</span></td>
                <td>
                  <form action="{{ route('maintenance.safety.release') }}" method="POST" class="d-inline">
                    @csrf
                    <input type="hidden" name="tool_id" value="{{ $t->id }}">
                    <button type="submit" class="btn btn-success btn-sm px-3">Release Tool</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Battery -->
      <div class="panel" id="panel-battery">
        <div class="fw-bold fs-5 mb-3">Battery Health Tracking</div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Tool</th><th>Charge Cycles</th><th>Health Capacity</th><th>Logged</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($batteryTools as $b)
              @php $cap = $b->health_percentage ?? 0; @endphp
              <tr>
                <td class="fw-bold">{{ $b->tool->title ?? 'N/A' }}</td>
                <td>{{ $b->charge_cycles }} cycles</td>
                <td>
                  <div class="progress" style="height:10px;">
                    <div class="progress-bar {{ $cap < 50 ? 'bg-danger' : ($cap < 80 ? 'bg-warning' : 'bg-success') }}" style="width:{{ $cap }}%"></div>
                  </div>
                  <small class="text-muted">{{ $cap }}%</small>
                </td>
                <td><small class="text-muted">{{ $b->logged_at ? $b->logged_at->format('M d, Y') : 'N/A' }}</small></td>
                <td><span class="badge {{ $cap < 50 ? 'bg-danger' : ($cap < 80 ? 'bg-warning text-dark' : 'bg-success') }}">{{ $cap < 50 ? 'Replace' : ($cap < 80 ? 'Degraded' : 'Healthy') }}</span></td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center p-4 text-muted">No battery health records found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Estimator -->
      <div class="panel" id="panel-estimator">
        <div class="fw-bold fs-5 mb-3">Repair Cost Estimator</div>
        <div class="row g-4">
          <div class="col-md-5">
            <div class="card p-4">
              <div class="mb-3">
                <label class="small fw-bold">Tool Category</label>
                <select class="form-select" id="estCat">
                  @foreach($categories as $cat)
                  <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="mb-3">
                <label class="small fw-bold">Issue Type</label>
                <select class="form-select" id="estIssue" onchange="calcEstimate()">
                  <option value="">-- Select Issue --</option>
                  @foreach($estimates as $est)
                  <option value="{{ $est->estimated_cost }}" data-parts="{{ number_format($est->estimated_cost * 0.6, 2) }}" data-labor="{{ number_format($est->estimated_cost * 0.4, 2) }}">{{ $est->issue_name }}</option>
                  @endforeach
                </select>
              </div>
              <div id="estimateBox" class="p-3 bg-light rounded border border-primary mt-3" style="display:none;">
                <div class="text-secondary small">Estimated Total</div>
                <div class="fs-4 fw-bold text-primary" id="estimateVal">0 EGP</div>
                <hr class="my-2">
                <div class="d-flex justify-content-between small text-muted">
                    <span>Parts: <span id="estParts">0</span> EGP</span>
                    <span>Labor: <span id="estLabor">0</span> EGP</span>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-7">
            <div class="card overflow-hidden">
              <table class="table align-middle mb-0">
                <thead class="table-light"><tr><th>Issue</th><th>Parts</th><th>Labor</th><th>Total</th></tr></thead>
                <tbody>
                  @foreach($estimates->take(8) as $est)
                  <tr>
                    <td>{{ $est->issue_name }}</td>
                    <td>{{ number_format($est->estimated_cost * 0.6, 2) }}</td>
                    <td>{{ number_format($est->estimated_cost * 0.4, 2) }}</td>
                    <td class="fw-bold text-primary">{{ number_format($est->estimated_cost, 2) }} EGP</td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel: Metrics -->
      <div class="panel" id="panel-metrics">
        <div class="fw-bold fs-5 mb-3">My Performance Metrics</div>
        <div class="row g-4 text-center">
          <div class="col-md-4">
            <div class="card p-4 border-top border-primary border-4">
              <div class="display-6 fw-bold text-primary">{{ $metrics->total_repairs_completed ?? 0 }}</div>
              <div class="text-secondary small fw-bold mt-2">REPAIRS COMPLETED</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-4 border-top border-success border-4">
              <div class="display-6 fw-bold text-success">{{ $metrics->success_rate_percentage ?? 0 }}%</div>
              <div class="text-secondary small fw-bold mt-2">SUCCESS RATE</div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-4 border-top border-info border-4">
              <div class="display-6 fw-bold text-info">{{ round(($metrics->avg_completion_time_minutes ?? 0) / 60, 1) }}h</div>
              <div class="text-secondary small fw-bold mt-2">AVG. TIME PER TASK</div>
            </div>
          </div>
        </div>
      </div>

      <!-- Panel: External -->
      <div class="panel" id="panel-external">
          <div class="fw-bold fs-5 mb-3">External Repairs</div>
          <div class="card overflow-hidden">
              <table class="table align-middle mb-0">
                  <thead class="table-light"><tr><th>Tool</th><th>Shop</th><th>Sent</th><th>Est. Return</th><th>Status</th></tr></thead>
                  <tbody>
                      @forelse($externalRepairs as $rep)
                      <tr>
                          <td class="fw-bold">{{ $rep->tool->title ?? 'N/A' }}</td>
                          <td>{{ $rep->shop_name }}</td>
                          <td>{{ $rep->dispatch_date }}</td>
                          <td>{{ $rep->expected_return_date ?? '—' }}</td>
                          <td><span class="badge {{ $rep->status == 'completed' ? 'bg-success' : 'bg-warning text-dark' }}">{{ ucfirst($rep->status) }}</span></td>
                      </tr>
                      @empty
                      <tr><td colspan="5" class="text-center text-muted p-4">No external repairs in progress.</td></tr>
                      @endforelse
                  </tbody>
              </table>
          </div>
      </div>

      <!-- Panel: Spare Parts -->
      <div class="panel" id="panel-parts">
        <div class="fw-bold fs-5 mb-3">Replacement Part Procurement</div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Part</th><th>Tool</th><th>Order Date</th><th>Est. Arrival</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($sparePartOrders as $order)
              <tr>
                <td class="fw-bold">{{ $order->part_name }}</td>
                <td>{{ $order->tool->title ?? 'N/A' }}</td>
                <td>{{ $order->order_date }}</td>
                <td>{{ $order->expected_arrival_date ?? '—' }}</td>
                <td>
                  @if($order->status == 'arrived') <span class="badge bg-success">Arrived</span>
                  @elseif($order->status == 'installed') <span class="badge bg-primary">Installed</span>
                  @else <span class="badge bg-warning text-dark">Ordered</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted p-4">No spare part orders found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Consumables -->
      <div class="panel" id="panel-consumables">
        <div class="fw-bold fs-5 mb-3">Consumables Inventory Tracker</div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Item</th><th>Current Stock</th><th>Min Level</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
              @forelse($consumables as $item)
              <tr>
                <td class="fw-bold">{{ $item->name }}</td>
                <td>{{ $item->stock_level ?? 0 }} {{ $item->unit ?? '' }}</td>
                <td>{{ $item->reorder_threshold ?? 1 }} {{ $item->unit ?? '' }}</td>
                <td>
                  @if(($item->stock_level ?? 0) <= ($item->reorder_threshold ?? 1))
                    <span class="badge bg-danger">Low Stock</span>
                  @else
                    <span class="badge bg-success">OK</span>
                  @endif
                </td>
                <td>
                  <form action="{{ route('maintenance.inventory.stock') }}" method="POST" class="d-flex gap-1 align-items-center">
                    @csrf
                    <input type="hidden" name="consumable_id" value="{{ $item->id }}">
                    <input type="number" name="quantity_used" class="form-control form-control-sm" style="width:70px" min="1" value="1" required>
                    <button type="submit" class="btn btn-outline-secondary btn-sm text-nowrap">Use Stock</button>
                  </form>
                </td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted p-4">No consumables tracked yet.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Warranty -->
      <div class="panel" id="panel-warranty">
        <div class="fw-bold fs-5 mb-3">Warranty Expiry Alerts</div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Tool</th><th>Owner</th><th>Expires</th><th>Days Left</th></tr></thead>
            <tbody>
              @forelse($warrantyTools as $t)
              @php
                $daysLeft = now()->diffInDays($t->warranty_expiry_date, false);
              @endphp
              <tr>
                <td class="fw-bold">{{ $t->title }}</td>
                <td>{{ $t->owner->name ?? 'N/A' }}</td>
                <td>{{ \Carbon\Carbon::parse($t->warranty_expiry_date)->format('M d, Y') }}</td>
                <td>
                  <span class="badge {{ $daysLeft <= 0 ? 'bg-danger' : ($daysLeft <= 14 ? 'bg-danger' : ($daysLeft <= 30 ? 'bg-warning text-dark' : 'bg-success')) }}">
                    {{ $daysLeft <= 0 ? 'Expired' : $daysLeft . ' days' }}
                  </span>
                </td>
              </tr>
              @empty
              <tr><td colspan="4" class="text-center text-muted p-4">No tools with warranty expiry data.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Disposal -->
      <div class="panel" id="panel-disposal">
        <div class="fw-bold fs-5 mb-3">Disposal / Recycle Workflow</div>
        <div class="card overflow-hidden">
          <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Tool</th><th>Reason</th><th>Method</th><th>Disposed At</th><th>Status</th></tr></thead>
            <tbody>
              @forelse($disposals as $d)
              <tr>
                <td class="fw-bold">{{ $d->tool->title ?? 'N/A' }}</td>
                <td>{{ $d->reason }}</td>
                <td><span class="badge bg-secondary">{{ ucfirst($d->disposal_method) }}</span></td>
                <td>{{ $d->disposed_at }}</td>
                <td><span class="badge bg-success">Completed</span></td>
              </tr>
              @empty
              <tr><td colspan="5" class="text-center text-muted p-4">No disposal records found.</td></tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Panel: Knowledge Base -->
      <div class="panel" id="panel-knowledgebase">
        <div class="fw-bold fs-5 mb-3">Technical Knowledge Base</div>
        <div class="row g-4">
          <div class="col-md-8">
            <div class="card overflow-hidden">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light"><tr><th>Article Title</th><th>Content Snippet</th><th>Author</th><th>Created</th></tr></thead>
                <tbody>
                  @forelse($articles as $article)
                  <tr>
                    <td class="fw-bold">{{ $article->title }}</td>
                    <td class="text-muted small">{{ Str::limit($article->content, 50) }}</td>
                    <td>{{ $article->author_id ?? 'System' }}</td>
                    <td>{{ $article->created_at ? $article->created_at->format('M d, Y') : '—' }}</td>
                  </tr>
                  @empty
                  <tr><td colspan="4" class="text-center text-muted p-4">No articles in the knowledge base yet.</td></tr>
                  @endforelse
                </tbody>
              </table>
            </div>
          </div>
          <div class="col-md-4">
            <div class="card p-4">
              <div class="fw-bold small mb-3">Contribute an Article</div>
              <form action="{{ route('maintenance.wiki.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                  <label class="small fw-bold">Article Title</label>
                  <input type="text" name="title" class="form-control form-control-sm" placeholder="e.g. How to fix a drill motor" required>
                </div>
                <div class="mb-3">
                  <label class="small fw-bold">Content</label>
                  <textarea name="content" class="form-control form-control-sm" rows="4" placeholder="Write your tip or guide..." required minlength="10"></textarea>
                </div>
                <button class="btn btn-outline-primary btn-sm w-100 fw-bold">Submit Tip</button>
              </form>
            </div>
          </div>
        </div>
      </div>


    </div>
  </div>
</div>

<script>
  function show(id, el) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    const target = document.getElementById('panel-' + id);
    if(target) target.classList.add('active');
    
    document.querySelectorAll('.sidebar .btn').forEach(b => b.classList.remove('active-link'));
    if(el) el.classList.add('active-link');
  }

  function calcEstimate() {
    const sel = document.getElementById('estIssue');
    const opt = sel.options[sel.selectedIndex];
    if(!opt.value) {
        document.getElementById('estimateBox').style.display = 'none';
        return;
    }
    document.getElementById('estimateVal').textContent = opt.value + ' EGP';
    document.getElementById('estParts').textContent = opt.getAttribute('data-parts');
    document.getElementById('estLabor').textContent = opt.getAttribute('data-labor');
    document.getElementById('estimateBox').style.display = 'block';
  }
</script>
</body>
</html>
