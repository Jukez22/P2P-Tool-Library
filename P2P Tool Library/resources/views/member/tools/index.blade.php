<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Browse Tools – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .tool-card { border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); transition: transform 0.2s; height: 100%; }
    .tool-card:hover { transform: translateY(-5px); }
    .tool-img { height: 180px; background: #eee; border-top-left-radius: 12px; border-top-right-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 3rem; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">3EDTAK</a>
    <div class="d-flex gap-2">
      <a href="{{ route('member.tools.create') }}" class="btn btn-light btn-sm">+ List Tool</a>
      <a href="{{ route('member.dashboard') }}" class="btn btn-outline-light btn-sm">Dashboard</a>
    </div>
  </div>
</nav>

<div class="container mb-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold m-0">Browse Equipment</h3>
    <div class="text-secondary">{{ $tools->count() }} tools available</div>
  </div>

  <div class="row g-4">
    @forelse($tools as $tool)
      <div class="col-md-3">
          <div class="card tool-card">
            <div class="tool-img">
              🛠️
              @if($tool->is_boosted)
                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">★ Boosted</span>
              @endif
            </div>
            <div class="card-body">
              <span class="badge bg-light text-primary mb-2">{{ $tool->category->name ?? 'Uncategorized' }}</span>
              <h5 class="card-title fw-bold mb-1">{{ $tool->title }}</h5>
              <p class="text-secondary small mb-3">
                By {{ $tool->owner->name }}
                @if(isset($tool->distance))
                   · {{ round($tool->distance, 1) }} km away
                @endif
              </p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold text-primary">{{ $tool->price }} EGP/day</span>
              <a href="{{ route('member.tools.show', $tool->id) }}" class="btn btn-primary btn-sm px-3">View</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12 text-center py-5">
        <h4 class="text-secondary">No tools available at the moment.</h4>
        <a href="{{ route('member.tools.create') }}" class="btn btn-primary mt-3">Be the first to list one!</a>
      </div>
    @endforelse
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
=======
<!-- Index View -->
>>>>>>> 8d0d19da599f4cc24cf668f06531e8ed97dc3973
