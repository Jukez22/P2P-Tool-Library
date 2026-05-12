<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Member Dashboard – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    :root {
      --sidebar-bg: #ffffff;
      --main-bg: #f8f9fa;
      --border-color: #dee2e6;
    }
    body { background-color: var(--main-bg); font-family: 'Segoe UI', sans-serif; color: #333; }
    
    .sidebar { min-height: 100vh; background-color: var(--sidebar-bg); border-right: 1px solid var(--border-color); }
    .sidebar .btn { font-size: 0.85rem; text-align: left; width: 100%; border: none; border-radius: 0; padding: 10px 15px; color: #6c757d; background: transparent; transition: 0.2s; }
    .sidebar .btn:hover { background-color: #f1f3f5; color: #0d6efd; }
    .sidebar .btn.active-link { background-color: #e7f1ff; color: #0d6efd !important; font-weight: bold; border-right: 4px solid #0d6efd; }
    
    .panel { display: none; }
    .panel.active { display: block; }

    .card { background: #fff; border: 1px solid var(--border-color); border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    .table { background: #fff; margin-bottom: 0; }
    .section-head { font-size: 0.68rem; text-transform: uppercase; color: #adb5bd; font-weight: bold; padding: 15px 15px 5px; margin: 0; }
    
    .progress { height: 8px; border-radius: 4px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">🛠️ 3EDTAK - ToolShare</a>
    <div class="d-flex align-items-center gap-3">
      <small class="fw-bold text-secondary">{{ $user->name }}</small>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-outline-secondary btn-sm">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">

    <div class="col-md-2 p-0 sidebar">
      <div class="p-3 border-bottom">
        <div class="fw-bold small">{{ $user->name }}</div>
        <div class="text-secondary" style="font-size:0.75rem;">Trust {{ $user->trust_score ?? '3.0' }} ★</div>
      </div>
      
      <div class="section-head">Overview</div>
      <button class="btn active-link" onclick="show('dashboard',this)">🏠 Dashboard</button>
      <a href="{{ route('member.tools.index') }}" class="btn d-block text-decoration-none">🔍 Browse Tools</a>
      <button class="btn" onclick="show('reservations',this)">📅 My Reservations</button>
      <button class="btn" onclick="show('messages',this)">💬 Messages</button>
      <button class="btn" onclick="show('reports',this)">🚩 Disputes & Reports</button>

      <div class="section-head">My Tools</div>
      <button class="btn" onclick="show('mytools',this)">🔧 My Listed Tools</button>
      <button class="btn" onclick="show('addtool',this)">➕ List a Tool</button>
      <button class="btn" onclick="show('calendar',this)">🗓️ Availability Calendar</button>

      <div class="section-head">Account</div>
      <button class="btn" onclick="show('trust',this)">⭐ Trust Score</button>
      <button class="btn" onclick="show('deposit',this)">💰 Deposit / Escrow</button>
      <button class="btn" onclick="show('referral',this)">🎁 Referral Rewards</button>
      <button class="btn" onclick="show('membership',this)">🏅 Membership</button>
      <button class="btn" onclick="show('profile',this)">⚙️ Profile Settings</button>
    </div>

    <div class="col-md-10 p-4">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4" role="alert" style="border-radius:12px; cursor:pointer;" onclick="this.style.display='none'">
          <strong>Success!</strong> {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      @endif

      <div class="panel active" id="panel-dashboard">
        <div class="fw-bold fs-5 mb-1">Dashboard</div>
        <div class="text-secondary small mb-3">Welcome back, {{ explode(' ', $user->name)[0] }}</div>
        <div class="row g-3 mb-4">
          <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $user->reservations->where('status', 'Active')->count() }}</div><small class="text-secondary">Active Borrowings</small></div></div>
          <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $user->tools->count() }}</div><small class="text-secondary">Tools Listed</small></div></div>
          <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ $user->trust_score ?? '0.0' }}</div><small class="text-secondary">Trust Score</small></div></div>
          <div class="col-md-3"><div class="card p-3 text-center"><div class="fs-4 fw-bold text-primary">{{ number_format($earningsThisMonth, 0) }} EGP</div><small class="text-secondary">Earnings This Month</small></div></div>
        </div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Current Borrowings</span>
                <button class="btn btn-outline-secondary btn-sm p-1 px-2" style="font-size: 0.7rem;" onclick="show('reservations',null)">View All</button>
              </div>
              <div class="card-body p-0">
                <table class="table table-hover mb-0 small">
                  <tbody>
                    @forelse($user->reservations->take(3) as $res)
                    <tr>
                      <td>🔩 {{ $res->tool->title }}</td>
                      <td><span class="badge {{ $res->status == 'Active' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $res->status }}</span></td>
                      <td class="text-secondary">Due {{ $res->end_datetime ? $res->end_datetime->format('M d') : 'N/A' }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted p-3">No active borrowings.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-light d-flex justify-content-between align-items-center">
                <span class="fw-bold small">Recent Messages</span>
                <button class="btn btn-outline-secondary btn-sm p-1 px-2" style="font-size: 0.7rem;" onclick="show('messages',null)">Open Inbox</button>
              </div>
              <ul class="list-group list-group-flush">
                @forelse($user->messagesReceived->sortByDesc('created_at')->take(3) as $msg)
                  <a href="{{ route('member.dashboard', ['panel' => 'messages', 'contact_id' => $msg->sender_id]) }}" class="list-group-item list-group-item-action small border-0 border-bottom">
                    <div class="d-flex justify-content-between align-items-center">
                      <div class="fw-bold text-primary">{{ $msg->sender->name }}</div>
                      <small class="text-muted" style="font-size: 0.65rem;">{{ $msg->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-secondary text-truncate" style="max-width: 100%;">{{ $msg->content }}</div>
                  </a>
                @empty
                  <li class="list-group-item small text-center text-muted p-3">No recent messages</li>
                @endforelse
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-light fw-bold small">My Listed Tools</div>
              <div class="card-body p-0">
                <table class="table table-hover mb-0 small">
                  <tbody>
                    @forelse($user->tools->take(3) as $tool)
                    <tr>
                      <td>🔧 {{ $tool->title }}</td>
                      <td class="text-secondary">{{ $tool->reservations->count() }} borrows</td>
                      <td>
                        @if($tool->reservations->where('status', 'Active')->count() > 0)
                          <span class="badge bg-warning text-dark">Borrowed</span>
                        @else
                          <span class="badge bg-success">Available</span>
                        @endif
                      </td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted p-3">No tools listed yet.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-light fw-bold small">Trust Score Breakdown</div>
              <div class="card-body">
                <div class="text-center mb-3">
                  <div class="fs-2 fw-bold text-primary">{{ $user->trust_score ?? '0.0' }}</div>
                  <div class="text-warning">
                    @php $score = floor($user->trust_score ?? 3); @endphp
                    @for($i = 1; $i <= 5; $i++)
                      {{ $i <= $score ? '★' : '☆' }}
                    @endfor
                  </div>
                  <small class="text-secondary">Based on {{ $totalTransactions }} transactions</small>
                </div>
                <div class="mb-2"><div class="d-flex justify-content-between small mb-1"><span>Return Punctuality</span><span class="text-primary fw-bold">100%</span></div><div class="progress"><div class="progress-bar" style="width:100%"></div></div></div>
                <div class="mb-2"><div class="d-flex justify-content-between small mb-1"><span>Tool Condition</span><span class="text-primary fw-bold">100%</span></div><div class="progress"><div class="progress-bar" style="width:100%"></div></div></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-reservations">
        <div class="fw-bold fs-5 mb-1">My Reservations</div>
        <div class="text-secondary small mb-3">Track all your borrowing requests and active rentals</div>
        <div class="card">
          <div class="card-body p-0">
            <table class="table table-hover mb-0 small text-center align-middle">
              <thead class="table-light"><tr><th>Tool</th><th>Lender</th><th>Dates</th><th>Cost</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                @forelse($user->reservations as $res)
                <tr>
                  <td>🔩 {{ $res->tool->title }}</td>
                  <td>{{ $res->tool->owner->name }}</td>
                  <td>{{ $res->start_datetime ? $res->start_datetime->format('M d') : 'N/A' }}–{{ $res->end_datetime ? $res->end_datetime->format('d') : 'N/A' }}</td>
                  <td class="fw-bold text-primary">{{ $res->total_price }} EGP</td>
                  <td><span class="badge {{ $res->status == 'Active' ? 'bg-success' : 'bg-warning text-dark' }}">{{ $res->status }}</span></td>
                  <td>
                    @if($res->status == 'Active')
                    <form action="{{ route('member.reservations.update', $res->id) }}" method="POST" class="d-inline">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="status" value="Completed">
                      <button type="submit" class="btn btn-primary btn-sm">Return</button>
                    </form>
                    @endif
                    <form action="{{ route('member.reservations.destroy', $res->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to cancel this request?');">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-link btn-sm text-danger text-decoration-none p-0 ms-2">Cancel</button>
                    </form>
                    <a href="{{ route('member.messages.show', $res->tool->owner_id) }}" class="btn btn-outline-secondary btn-sm ms-2" title="Message Lender">💬</a>
                    <button class="btn btn-outline-danger btn-sm ms-2" title="Report Issue" onclick="setReportModal({{ $res->id }}, {{ $res->tool_id }}, {{ $res->tool->owner_id }})">🚩</button>
                  </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted p-4">You haven't made any reservations yet.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-messages">
        <div class="fw-bold fs-5 mb-1">Messages</div>
        <div class="text-secondary small mb-3">Direct communication with lenders and borrowers</div>
        <div class="card overflow-hidden">
          <div class="row g-0" style="min-height:450px;">
            <div class="col-md-4 border-end bg-light overflow-auto" style="max-height:450px;">
              <div class="p-2 border-bottom"><input type="text" class="form-control form-control-sm" placeholder="Search contacts..."/></div>
              <div class="list-group list-group-flush">
                @forelse($contacts as $contact)
                <a href="{{ route('member.dashboard', ['panel' => 'messages', 'contact_id' => $contact->id]) }}" 
                   class="list-group-item list-group-item-action {{ ($selectedContact->id ?? null) == $contact->id ? 'active' : '' }} py-3">
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="fw-bold">{{ $contact->name }}</div>
                    <small class="opacity-75">{{ $contact->last_message ? $contact->last_message->created_at->format('g:i A') : '' }}</small>
                  </div>
                  <div class="small opacity-75 text-truncate">{{ $contact->last_message->content ?? 'No messages yet' }}</div>
                </a>
                @empty
                <div class="p-4 text-center text-muted small">No conversations yet.</div>
                @endforelse
              </div>
            </div>
            <div class="col-md-8 d-flex flex-column" style="height:450px;">
              @if($selectedContact)
                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-white">
                  <div>
                    <div class="fw-bold">{{ $selectedContact->name }}</div>
                    <div class="text-success small"><span class="badge bg-success rounded-circle p-1"></span> Online</div>
                  </div>
                </div>
                <div class="flex-grow-1 p-3 overflow-auto bg-white" id="chatWindow">
                  @forelse($activeMessages as $msg)
                  <div class="mb-3 {{ $msg->sender_id == auth()->id() ? 'text-end' : '' }}">
                    <div class="small text-muted mb-1">{{ $msg->sender_id == auth()->id() ? 'You' : $selectedContact->name }}, {{ $msg->created_at->format('H:i') }}</div>
                    <div class="{{ $msg->sender_id == auth()->id() ? 'bg-primary text-white' : 'bg-light border' }} rounded-4 px-3 py-2 d-inline-block shadow-sm" style="max-width:75%; border-radius: 20px !important;">
                      {{ $msg->content }}
                    </div>
                  </div>
                  @empty
                  <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted opacity-50">
                    <div class="fs-1">✉️</div>
                    <div>No messages yet. Send a greeting!</div>
                  </div>
                  @endforelse
                </div>
                <div class="p-3 border-top bg-light">
                  <form action="{{ route('member.messages.store') }}" method="POST" class="d-flex gap-2">
                    @csrf
                    <input type="hidden" name="receiver_id" value="{{ $selectedContact->id }}">
                    <input type="text" name="content" class="form-control" placeholder="Type your message..." required autocomplete="off"/>
                    <button type="submit" class="btn btn-primary px-4 fw-bold">Send</button>
                  </form>
                </div>
              @else
                <div class="h-100 d-flex flex-column align-items-center justify-content-center text-muted opacity-50 bg-white">
                  <div class="display-4">💬</div>
                  <p>Select a contact to start messaging</p>
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-mytools">
        <div class="fw-bold fs-5 mb-1">My Listed Tools</div>
        <div class="text-secondary small mb-3">Manage your listings and view earnings</div>
        <div class="card">
          <div class="card-body p-0">
            <table class="table table-hover mb-0 small text-center align-middle">
              <thead class="table-light"><tr><th>Tool</th><th>Category</th><th>Price</th><th>Condition</th><th>Status</th><th>Action</th></tr></thead>
              <tbody>
                @forelse($user->tools as $tool)
                <tr>
                  <td>🔧 {{ $tool->title }}</td>
                  <td>{{ $tool->category->name ?? 'N/A' }}</td>
                  <td class="fw-bold text-primary">{{ $tool->price }} EGP</td>
                  <td><span class="badge bg-light text-dark border">{{ $tool->condition_status }}</span></td>
                  <td>
                    @if($tool->reservations->where('status', 'Active')->count() > 0)
                      <span class="badge bg-warning text-dark">Borrowed</span>
                    @else
                      <span class="badge bg-success">Available</span>
                    @endif
                    <small class="d-block text-secondary mt-1">{{ $tool->reservations->count() }} total borrows</small>
                  </td>
                  <td>
                    <div class="d-flex justify-content-center gap-1">
                      <a href="{{ route('member.tools.edit', $tool->id) }}" class="btn btn-outline-primary btn-sm px-3">Edit</a>
                    </div>
                  </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center text-muted p-4">You haven't listed any tools yet.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-addtool">
        <div class="fw-bold fs-5 mb-1">List a New Tool</div>
        <div class="text-secondary small mb-3">Fill in the details to start lending your tool</div>
        <div class="card" style="max-width:650px;">
          <div class="card-body p-4">
            <form action="{{ route('member.tools.store') }}" method="POST" enctype="multipart/form-data">
              @csrf
              <div class="mb-3">
                <label class="form-label small fw-bold">Tool Title</label>
                <input type="text" name="title" class="form-control" placeholder="e.g. Bosch Professional Drill" required/>
              </div>

              <div class="row g-3 mb-3">
                <div class="col-6">
                  <label class="form-label small fw-bold">Price per Day (EGP)</label>
                  <input type="number" name="price" class="form-control" placeholder="50" required/>
                </div>
                <div class="col-6">
                  <label class="form-label small fw-bold">Category</label>
                  <select name="category_id" class="form-select" required>
                    @foreach($categories as $category)
                      <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold">Condition</label>
                <select name="condition_status" class="form-select" required>
                  <option value="Excellent">Excellent</option>
                  <option value="Good" selected>Good</option>
                  <option value="Fair">Fair</option>
                  <option value="Needs Repair">Needs Repair</option>
                </select>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold">Full Description</label>
                <textarea name="description" class="form-control" rows="4" placeholder="Describe the tool, its features, and any rules for usage..." required></textarea>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-info">Compatibility Tags (Optional)</label>
                <input type="text" name="compatibility_tags" class="form-control" placeholder="e.g. SDS Plus, M18 Battery, 10-inch Blade"/>
                <div class="form-text small">List parts or standards this tool is compatible with, separated by commas.</div>
              </div>

              <div class="mb-3">
                <label class="form-label small fw-bold text-primary">Documentation (Optional)</label>
                <div class="input-group mb-2">
                  <span class="input-group-text small">📄 Manual URL</span>
                  <input type="url" name="manual_url" class="form-control" placeholder="Link to PDF manual"/>
                </div>
                <div class="input-group">
                  <span class="input-group-text small">🎥 Video URL</span>
                  <input type="url" name="video_url" class="form-control" placeholder="Link to safety video"/>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">List Tool Now</button>
            </form>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-calendar">
        <div class="fw-bold fs-5 mb-1">Availability Calendar</div>
        <div class="text-secondary small mb-3">Set available dates and buffer periods</div>
        <div class="card" style="max-width:460px;">
          <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <button class="btn btn-outline-secondary btn-sm">← Prev</button>
              <strong class="text-dark">May 2026</strong>
              <button class="btn btn-outline-secondary btn-sm">Next →</button>
            </div>
            <div class="row row-cols-7 g-1 text-center mb-2">
              <div class="col"><small class="text-secondary fw-bold">Su</small></div><div class="col"><small class="text-secondary fw-bold">Mo</small></div><div class="col"><small class="text-secondary fw-bold">Tu</small></div><div class="col"><small class="text-secondary fw-bold">We</small></div><div class="col"><small class="text-secondary fw-bold">Th</small></div><div class="col"><small class="text-secondary fw-bold">Fr</small></div><div class="col"><small class="text-secondary fw-bold">Sa</small></div>
            </div>
            <div class="row row-cols-7 g-1 text-center small">
              <div class="col"></div><div class="col"></div><div class="col"></div>
              <div class="col"><span class="badge bg-success w-100">1</span></div>
              <div class="col"><span class="badge bg-success w-100">2</span></div>
              <div class="col"><span class="badge bg-primary w-100 shadow">3</span></div>
              <div class="col"><span class="badge bg-success w-100">4</span></div>
              <div class="col"><span class="badge bg-warning text-dark w-100">5</span></div>
              <div class="col"><span class="badge bg-warning text-dark w-100">6</span></div>
              <div class="col"><span class="badge bg-warning text-dark w-100">7</span></div>
              <div class="col"><span class="badge bg-success w-100">8</span></div>
              <div class="col"><span class="badge bg-success w-100">9</span></div>
              <div class="col"><span class="badge bg-light text-dark border w-100">10</span></div>
              <div class="col"><span class="badge bg-light text-dark border w-100">11</span></div>
              <div class="col"><span class="badge bg-success w-100">12</span></div>
              <div class="col"><span class="badge bg-success w-100">13</span></div>
              <div class="col"><span class="badge bg-light text-dark border w-100">14</span></div>
            </div>
            <div class="d-flex gap-3 mt-4 small text-secondary">
              <span><span class="badge bg-success">·</span> Available</span>
              <span><span class="badge bg-warning text-dark">·</span> Booked</span>
              <span><span class="badge bg-primary">·</span> Today</span>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-trust">
        <div class="fw-bold fs-5 mb-1">Trust Score</div>
        <div class="text-secondary small mb-3">Your community reputation</div>
        <div class="card p-3" style="max-width:460px;">
          <div class="text-center mb-4">
            <div class="fs-1 fw-bold text-primary">{{ $user->trust_score ?? '3.0' }}</div>
            <div class="text-warning fs-5">
              @php $score = floor($user->trust_score ?? 3); @endphp
              @for($i = 1; $i <= 5; $i++)
                {{ $i <= $score ? '★' : '☆' }}
              @endfor
            </div>
            <small class="text-secondary">Based on {{ $totalTransactions }} transactions</small>
          </div>
          <div class="mb-3"><div class="d-flex justify-content-between small mb-1"><span>Return Punctuality</span><span class="text-primary fw-bold">100%</span></div><div class="progress"><div class="progress-bar bg-primary" style="width:100%"></div></div></div>
          <div class="mb-3"><div class="d-flex justify-content-between small mb-1"><span>Tool Condition</span><span class="text-primary fw-bold">100%</span></div><div class="progress"><div class="progress-bar bg-primary" style="width:100%"></div></div></div>
          <div class="mb-3"><div class="d-flex justify-content-between small mb-1"><span>Communication</span><span class="text-primary fw-bold">100%</span></div><div class="progress"><div class="progress-bar bg-primary" style="width:100%"></div></div></div>
          <div class="mb-3"><div class="d-flex justify-content-between small mb-1"><span>Zero Disputes</span><span class="text-primary fw-bold">100%</span></div><div class="progress"><div class="progress-bar bg-success" style="width:100%"></div></div></div>
        </div>
      </div>

      <div class="panel" id="panel-deposit">
        <div class="fw-bold fs-5 mb-1">Deposit & Escrow</div>
        <div class="text-secondary small mb-3">Insurance deposits held securely</div>
        <div class="row g-3 mb-4 text-center">
          <div class="col-md-4"><div class="card p-3 shadow-sm"><div class="fs-5 fw-bold text-primary">0 EGP</div><small class="text-secondary">Held in Escrow</small></div></div>
          <div class="col-md-4"><div class="card p-3 shadow-sm"><div class="fs-5 fw-bold text-primary">0 EGP</div><small class="text-secondary">Total Released</small></div></div>
          <div class="col-md-4"><div class="card p-3 shadow-sm"><div class="fs-5 fw-bold text-danger">0 EGP</div><small class="text-secondary">Forfeited</small></div></div>
        </div>
        <div class="card">
          <div class="card-header bg-light fw-bold small">Transactions</div>
          <div class="card-body p-0">
            <table class="table table-hover mb-0 small text-center align-middle">
              <thead class="table-light"><tr><th>Tool</th><th>Date</th><th>Amount</th><th>Status</th></tr></thead>
              <tbody>
                <tr><td colspan="4" class="text-center text-muted p-4">No recent transactions.</td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-referral">
        <div class="fw-bold fs-5 mb-1">Referral Rewards</div>
        <div class="text-secondary small mb-3">Earn for every new verified lender</div>
        <div class="row g-3">
          <div class="col-md-6">
            <div class="card p-3">
              <div class="fw-bold small mb-2">Your Referral Link</div>
              <div class="input-group mb-3">
                <input type="text" class="form-control form-control-sm bg-light" value="toolshare.eg/ref/{{ strtoupper(explode(' ', $user->name)[0]) }}{{ $user->id }}" readonly/>
                <button class="btn btn-primary btn-sm" onclick="alert('Copied!')">Copy</button>
              </div>
              <div class="bg-primary bg-opacity-10 p-3 rounded text-center border border-primary"><div class="fs-4 fw-bold text-primary">0 EGP</div><small class="text-secondary">Available Credits</small></div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card">
              <div class="card-header bg-light fw-bold small">Referral History</div>
              <div class="card-body p-0">
                <table class="table mb-0 small">
                  <thead class="table-light"><tr><th>Name</th><th>Joined</th><th>Credit</th></tr></thead>
                  <tbody>
                    @forelse($user->referrals as $ref)
                    <tr>
                      <td>{{ $ref->referredUser->name }}</td>
                      <td>{{ $ref->created_at->format('Y-m-d') }}</td>
                      <td class="text-success">+{{ $ref->reward }} EGP</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center text-muted p-3">No referrals yet.</td></tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="panel" id="panel-membership">
        <div class="fw-bold fs-5 mb-1">Membership</div>
        <div class="text-secondary small mb-3">Upgrade your tier to unlock features</div>
        <div class="card p-4 text-center">
            <h4 class="fw-bold">{{ strtoupper($user->membershipTier->name ?? 'CASUAL') }} MEMBER</h4>
            <p class="text-secondary">Your current trust score is {{ $user->trust_score ?? '0.0' }}. 
              @if(($user->trust_score ?? 0) < 4.5)
              Keep lending and returning on time to reach 4.5★ and unlock PRO features!
              @else
              You have unlocked all premium features!
              @endif
            </p>
        </div>
      </div>

      <div class="panel" id="panel-profile">
        <div class="fw-bold fs-5 mb-1">Profile Settings</div>
        <div class="text-secondary small mb-3">Manage your personal information and verification</div>
        <div class="card p-4">
          <form action="{{ route('member.profile.update', $user->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row g-3">
              <div class="col-md-6"><label class="small fw-bold">Full Name</label><input type="text" name="name" class="form-control" value="{{ $user->name }}" required></div>
              <div class="col-md-6"><label class="small fw-bold">Phone Number</label><input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required></div>
              <div class="col-12"><label class="small fw-bold">Address</label><input type="text" name="address" class="form-control" value="{{ $user->address }}"></div>
              <div class="col-md-6"><label class="small fw-bold">National ID (for verification)</label><input type="text" name="national_id" class="form-control" value="{{ $user->national_id }}"></div>
              <div class="col-md-6 d-flex align-items-end">
                @if($user->is_verified)
                  <span class="badge bg-success p-2 px-3">✓ Verified Account</span>
                @else
                  <span class="badge bg-warning text-dark p-2 px-3">! Verification Pending</span>
                @endif
              </div>
              <div class="col-12 mt-4"><button type="submit" class="btn btn-primary px-5">Save Changes</button></div>
            </div>
          </form>

          <hr class="my-4"/>

          <h5 class="fw-bold mb-3 fs-6">Change Password</h5>
          <form action="{{ route('member.profile.changePassword') }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
              <div class="col-md-4">
                <label class="form-label fw-bold small">Current Password</label>
                <input type="password" name="current_password" class="form-control" required/>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-bold small">New Password</label>
                <input type="password" name="new_password" class="form-control" required/>
              </div>
              <div class="col-md-4">
                <label class="form-label fw-bold small">Confirm New Password</label>
                <input type="password" name="new_password_confirmation" class="form-control" required/>
              </div>
              <div class="col-12 mt-3">
                <button type="submit" class="btn btn-outline-primary px-4">Update Password</button>
              </div>
            </div>
          </form>
        </div>
      </div>

      <div class="panel" id="panel-reports">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div>
            <div class="fw-bold fs-5 mb-1">Disputes & Reports</div>
            <div class="text-secondary small">Track your damage reports and system disputes</div>
          </div>
          <button class="btn btn-danger btn-sm px-3 fw-bold" onclick="setReportModal(null, null, null)">File New Dispute</button>
        </div>
        <div class="card overflow-hidden">
          <div class="card-body p-0">
            <table class="table table-hover mb-0 small text-center align-middle">
              <thead class="table-light"><tr><th>Date</th><th>Reason</th><th>Description</th><th>Status</th></tr></thead>
              <tbody>
                @forelse($reports as $report)
                <tr>
                  <td>{{ $report->created_at->format('Y-m-d') }}</td>
                  <td><span class="badge bg-light text-dark border">{{ ucfirst(str_replace('_', ' ', $report->reason)) }}</span></td>
                  <td class="text-start">{{ Str::limit($report->description, 50) }}</td>
                  <td>
                    @if($report->status == 'pending') <span class="badge bg-warning text-dark">Pending Review</span>
                    @elseif($report->status == 'resolved') <span class="badge bg-success">Resolved</span>
                    @else <span class="badge bg-secondary">Dismissed</span> @endif
                  </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center text-muted p-4">No reports submitted yet.</td></tr>
                @endforelse
              </tbody>
            </table>
          </div>
        </div>
      </div>

    </div></div></div>

<div class="modal fade" id="reportModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header">
        <h5 class="modal-title fw-bold">Report Issue / Damage</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('member.reports.store') }}" method="POST">
        @csrf
        <input type="hidden" name="reservation_id" id="rep_res_id">
        <input type="hidden" name="reported_tool_id" id="rep_tool_id">
        <input type="hidden" name="reported_user_id" id="rep_user_id">
        <div class="modal-body">
          <div class="mb-3">
            <label class="small fw-bold">Reason</label>
            <select name="reason" class="form-select" required>
              <option value="damaged_tool">Damaged Tool</option>
              <option value="late_return">Late Return</option>
              <option value="no_show">No Show</option>
              <option value="fraud">Fraud / Scam</option>
              <option value="other">Other</option>
            </select>
          </div>
          <div class="mb-3">
            <label class="small fw-bold">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Please describe the issue in detail..." required minlength="10"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger w-100 fw-bold">Submit Report</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
  function setReportModal(resId, toolId, userId) {
    document.getElementById('rep_res_id').value = resId;
    document.getElementById('rep_tool_id').value = toolId;
    document.getElementById('rep_user_id').value = userId;
    new bootstrap.Modal(document.getElementById('reportModal')).show();
  }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function show(id, el) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    const target = document.getElementById('panel-' + id);
    if(target) target.classList.add('active');
    
    document.querySelectorAll('.sidebar .btn').forEach(b => {
      b.classList.remove('active-link');
    });
    if (el) {
      el.classList.add('active-link');
    }
  }

  // Initialize from URL parameters
  window.addEventListener('load', () => {
    const params = new URLSearchParams(window.location.search);
    const panel = params.get('panel') || 'dashboard';
    const btn = document.querySelector(`button[onclick*="'${panel}'"]`);
    show(panel, btn);
    
    // Auto-scroll chat to bottom
    const chat = document.getElementById('chatWindow');
    if(chat) chat.scrollTop = chat.scrollHeight;
  });
</script>
</body>
</html>