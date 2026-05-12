<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Browse Tools – ToolShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; color: #333; }
    .navbar { background-color: #fff !important; border-bottom: 2px solid #0d6efd; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .card { background: white; border: 1px solid #dee2e6; border-radius: 10px; transition: transform 0.2s; }
    .card:hover { transform: translateY(-3px); }
    .filter-card { position: sticky; top: 90px; }
    .btn-primary { background-color: #0d6efd; border: none; }
    .price-text { color: #0d6efd; font-weight: bold; }
    #pbox { background-color: #e7f1ff; border: 1px solid #b6d4fe; border-radius: 8px; color: #084298; }
    .titem { transition: 0.2s; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <div class="container-fluid px-lg-5">
    <a class="navbar-brand fw-bold text-primary" href="{{ route('member.dashboard') }}">🛠️ 3EDTAK - Browse Tools</a>
    <div class="ms-auto d-flex gap-2">
      <a href="{{ route('member.dashboard') }}" class="btn btn-outline-primary btn-sm px-3">My Dashboard</a>
      <form action="{{ route('logout') }}" method="POST" class="m-0">
        @csrf
        <button type="submit" class="btn btn-primary btn-sm text-white px-3">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid py-4 px-lg-5">
  <div class="row g-4">
    <div class="col-md-3">
      <div class="card filter-card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
          <span class="fw-bold small">Filters</span>
          <button class="btn btn-link btn-sm text-decoration-none p-0 small" onclick="resetF()">Reset All</button>
        </div>
        <div class="card-body">
          <div class="mb-4">
            <div class="text-muted mb-2 fw-bold" style="font-size:0.7rem;">CATEGORY</div>
            @foreach($categories as $category)
            <div class="form-check small mb-1">
              <input class="form-check-input cat-check" type="checkbox" value="{{ $category->name }}" checked onchange="filterTools()"/>
              <label class="form-check-label">{{ $category->name }}</label>
            </div>
            @endforeach
          </div>
          <div class="mb-4">
            <div class="text-muted mb-2 fw-bold" style="font-size:0.7rem;">DISTANCE</div>
            <input type="range" class="form-range" id="distRange" min="1" max="50" value="50" oninput="document.getElementById('dv').textContent=this.value; filterTools()"/>
            <small class="text-muted">Within <span id="dv">50</span> km</small>
          </div>
          <div class="mb-3">
            <div class="text-muted mb-2 fw-bold" style="font-size:0.7rem;">MIN. TRUST</div>
            <div class="btn-group btn-group-sm w-100">
              <button class="btn btn-outline-primary active" id="t4" onclick="setTrust(4)">4★+</button>
              <button class="btn btn-outline-primary" id="t5" onclick="setTrust(5)">5★</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-9">
      <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-3 rounded border shadow-sm">
        <div class="input-group input-group-sm w-50">
          <span class="input-group-text bg-white border-end-0">🔍</span>
          <input type="text" class="form-control border-start-0" id="srch" placeholder="Search for tools (drill, saw, printer...)" oninput="filterTools()"/>
        </div>
        <small class="text-muted">Found <span id="cnt" class="fw-bold text-primary">{{ $tools->count() }}</span> tools</small>
      </div>

      <div class="row g-3" id="toolGrid">
        @forelse($tools as $tool)
        <div class="col-md-4 titem" data-cat="{{ $tool->category->name ?? 'Uncategorized' }}" data-dist="{{ rand(1, 10) }}.{{ rand(1, 9) }}" data-trust="{{ $tool->owner->trust_score ?? '0.0' }}">
          <div class="card h-100 shadow-sm border-0">
            <div class="card-header bg-white small d-flex justify-content-between py-3">
              <span class="fw-bold">{{ $tool->title }}</span>
              @if($tool->is_boosted)
              <span class="badge bg-warning text-dark">Boosted</span>
              @else
              <span class="badge bg-success bg-opacity-10 text-success">Available</span>
              @endif
            </div>
            <div class="card-body">
              <div class="text-muted mb-2 small">{{ $tool->owner->name }} · {{ $tool->owner->trust_score ?? '0.0' }}★</div>
              <div class="d-flex justify-content-between mb-3">
                <span class="price-text">{{ $tool->price }} EGP/day</span>
                <span class="small text-secondary">📍 {{ rand(1, 10) }} km</span>
              </div>
              <button class="btn btn-primary btn-sm w-100 fw-bold" onclick="openBook('{{ addslashes($tool->title) }}', {{ $tool->price }}, 200, {{ $tool->id }}, {{ $tool->owner_id }})">Book Now</button>
            </div>
          </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <div class="display-1 text-muted opacity-25">🛠️</div>
            <p class="text-secondary mt-3">No tools found matching your criteria.</p>
        </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<!-- Booking Modal -->
<div class="modal fade" id="bookModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content shadow">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold" id="mTitle">Book Tool</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="{{ route('member.reservations.store') }}" method="POST" onsubmit="alert('Sending booking request to server...');">
        @csrf
        <input type="hidden" name="tool_id" id="modal_tool_id">
        <div class="modal-body">
          <div class="row g-2 mb-3">
            <div class="col-6"><label class="small fw-bold">Start Date</label><input type="date" name="start_datetime" class="form-control form-control-sm" id="sd" onchange="calc()" required/></div>
            <div class="col-6"><label class="small fw-bold">End Date</label><input type="date" name="end_datetime" class="form-control form-control-sm" id="ed" onchange="calc()" required/></div>
          </div>
          <div id="pbox" class="p-3 mb-2 small" style="display:none;">
            <div class="d-flex justify-content-between mb-1"><span>Rental Rate</span><span id="pr1"></span></div>
            <div class="d-flex justify-content-between border-bottom border-secondary pb-1 mb-1"><span>Duration</span><span id="pr2"></span></div>
            <div class="d-flex justify-content-between mb-1"><span>Service Fee (5%)</span><span id="pr3"></span></div>
            <div class="d-flex justify-content-between mb-1"><span>Refundable Deposit</span><span id="pr4"></span></div>
            <hr class="my-2">
            <div class="d-flex justify-content-between fw-bold fs-6"><span>Grand Total</span><span id="pr5"></span></div>
          </div>
        </div>
        <div class="modal-footer border-0 d-flex gap-2">
          <button type="submit" class="btn btn-primary flex-grow-1 py-2 fw-bold">Confirm Booking Request</button>
          <a id="msgBtn" href="#" class="btn btn-outline-primary flex-grow-1 py-2 fw-bold">Message Lender</a>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let currentTool = {};
  let trustFilter = 0; // Default to 0 so all show initially

  function openBook(n, r, d, id, ownerId) {
    currentTool = {r, d};
    document.getElementById('mTitle').innerText = 'Book ' + n;
    document.getElementById('modal_tool_id').value = id;
    document.getElementById('msgBtn').href = "{{ url('member/messages') }}/" + ownerId;
    document.getElementById('sd').value = new Date().toISOString().split('T')[0];
    document.getElementById('pbox').style.display = 'none';
    new bootstrap.Modal(document.getElementById('bookModal')).show();
  }

  function calc() {
    const s = document.getElementById('sd').value, e = document.getElementById('ed').value;
    if(!s || !e) return;
    const diff = new Date(e) - new Date(s);
    if(diff < 0) return;
    
    const days = Math.max(1, Math.round(diff / 86400000) + 1);
    const rental = currentTool.r * days, fee = Math.round(rental * 0.05);
    
    document.getElementById('pr1').innerText = rental + ' EGP';
    document.getElementById('pr2').textContent = days + ' day(s)';
    document.getElementById('pr3').innerText = fee + ' EGP';
    document.getElementById('pr4').innerText = currentTool.d + ' EGP';
    document.getElementById('pr5').innerText = (rental + fee + currentTool.d) + ' EGP';
    document.getElementById('pbox').style.display = 'block';
  }

  function filterTools() {
    const q = document.getElementById('srch').value.toLowerCase().trim();
    const maxD = parseFloat(document.getElementById('distRange').value) || 50;
    const checkedCats = Array.from(document.querySelectorAll('.cat-check:checked')).map(cb => cb.value);
    const totalCats = document.querySelectorAll('.cat-check').length;
    let count = 0;

    document.querySelectorAll('.titem').forEach(el => {
      const c = el.getAttribute('data-cat');
      const d = parseFloat(el.getAttribute('data-dist')) || 0;
      const t = parseFloat(el.getAttribute('data-trust')) || 0;
      
      // 1. Search Check
      const sMatch = !q || el.innerText.toLowerCase().includes(q);
      
      // 2. Category Check: If all checked or none checked, show all. Otherwise, match selected.
      const cMatch = (checkedCats.length === totalCats || checkedCats.length === 0) || checkedCats.includes(c);
      
      // 3. Distance Check
      const dMatch = d <= maxD;
      
      // 4. Trust Check
      const tMatch = t >= trustFilter;

      if (sMatch && cMatch && dMatch && tMatch) {
        el.style.display = '';
        count++;
      } else {
        el.style.display = 'none';
      }
    });
    document.getElementById('cnt').innerText = count;
  }

  function setTrust(v) {
    trustFilter = (trustFilter === v) ? 0 : v;
    if(document.getElementById('t4')) document.getElementById('t4').classList.toggle('active', trustFilter===4);
    if(document.getElementById('t5')) document.getElementById('t5').classList.toggle('active', trustFilter===5);
    filterTools();
  }

  function resetF() {
    // 1. Reset Inputs
    document.getElementById('srch').value = '';
    document.getElementById('distRange').value = 50;
    document.getElementById('dv').textContent = 50;
    document.querySelectorAll('.cat-check').forEach(cb => cb.checked = true);
    trustFilter = 0;
    
    // 2. Clear Active UI
    if(document.getElementById('t4')) document.getElementById('t4').classList.remove('active');
    if(document.getElementById('t5')) document.getElementById('t5').classList.remove('active');
    
    // 3. Force all tools to be visible immediately
    document.querySelectorAll('.titem').forEach(el => el.style.display = '');
    
    // 4. Recalculate
    filterTools();
  }
</script>
</body>
</html>
