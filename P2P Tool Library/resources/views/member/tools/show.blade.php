<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>{{ $tool->title }} – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .tool-hero-img { height: 350px; background: #eee; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 5rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">3EDTAK</a>
    <a href="{{ route('member.tools.index') }}" class="btn btn-outline-light btn-sm">Back to Browse</a>
  </div>
</nav>

<div class="container mb-5">
  <div class="row">
    <div class="col-md-7">
      <div class="tool-hero-img mb-4">🛠️</div>
      <div class="card p-4">
        <div class="d-flex justify-content-between align-items-start mb-3">
          <div>
            <span class="badge bg-primary mb-2">{{ $tool->category->name ?? 'Uncategorized' }}</span>
            <h2 class="fw-bold">{{ $tool->title }}</h2>
          </div>
          <div class="text-end">
            <h3 class="fw-bold text-primary mb-0">{{ $tool->price }} EGP</h3>
            <small class="text-secondary">Per Day</small>
          </div>
        </div>
        
        <hr/>
        
        <h5 class="fw-bold">Description</h5>
        <p class="text-secondary">{{ $tool->description }}</p>
        
        <div class="row mt-4">
          <div class="col-6">
            <div class="small text-secondary fw-bold">CONDITION</div>
            <div class="fw-bold text-dark">{{ $tool->condition_status }}</div>
          </div>
          <div class="col-6 text-end">
            <div class="small text-secondary fw-bold">LOCATION</div>
            <div class="fw-bold text-dark">Maadi, Cairo</div>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-md-5">
      <div class="card p-4 mb-4">
        <h5 class="fw-bold mb-3">Borrow this Tool</h5>
        
        @if($tool->owner_id == auth()->id())
          <div class="alert alert-info">You own this tool.</div>
          <a href="{{ route('member.tools.edit', $tool->id) }}" class="btn btn-outline-primary w-100">Edit Listing</a>
        @else
          <form action="{{ route('member.reservations.store') }}" method="POST">
            @csrf
            <input type="hidden" name="tool_id" value="{{ $tool->id }}"/>
            <div class="mb-3">
              <label class="form-label small fw-bold">Start Date</label>
              <input type="date" name="start_date" class="form-control" required/>
            </div>
            <div class="mb-3">
              <label class="form-label small fw-bold">End Date</label>
              <input type="date" name="end_date" class="form-control" required/>
            </div>
            <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Request to Borrow</button>
          </form>
          
          <div class="mt-3 text-center">
            <a href="{{ route('member.messages.show', $tool->owner_id) }}" class="text-decoration-none small fw-bold">Message Lender</a>
          </div>
        @endif
      </div>
      
      <div class="card p-3 d-flex flex-row align-items-center gap-3">
        <div class="bg-light rounded-circle p-3" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">👤</div>
        <div>
          <div class="small text-secondary fw-bold">LENDER</div>
          <div class="fw-bold">{{ $tool->owner->name }}</div>
          <div class="text-warning small">★★★★★ (4.8)</div>
        </div>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
