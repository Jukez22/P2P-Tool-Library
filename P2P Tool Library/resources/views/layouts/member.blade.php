<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>@yield('title', 'Member Dashboard - ToolShare')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    :root { --primary-gradient: linear-gradient(135deg, #0d6efd 0%, #0043a8 100%); }
    body { background-color: #f0f2f5; font-family: 'Inter', system-ui, -apple-system, sans-serif; color: #1a1d20; }
    .sidebar { background: white; min-height: 100vh; border-right: 1px solid #e0e4e9; }
    .sidebar .btn { width: 100%; text-align: left; padding: 0.75rem 1.25rem; border: none; border-radius: 0; color: #495057; font-size: 0.9rem; transition: all 0.2s; }
    .sidebar .btn:hover { background: #f8f9fa; color: #0d6efd; }
    .sidebar .active-link { background: #e7f1ff; color: #0d6efd; border-right: 3px solid #0d6efd; font-weight: 600; }
    .section-head { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #adb5bd; padding: 1.5rem 1.25rem 0.5rem; letter-spacing: 0.05rem; }
    .card { border: none; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); }
    .navbar { background: white !important; border-bottom: 1px solid #e0e4e9; }
    @yield('styles')
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg py-3 sticky-top">
  <div class="container-fluid px-4">
    <a class="navbar-brand fw-bold text-primary fs-4" href="{{ route('member.dashboard') }}">3EDTAK</a>
    <div class="ms-auto d-flex align-items-center gap-3">
      <div class="text-end d-none d-sm-block">
        <div class="fw-bold small">{{ auth()->user()->name }}</div>
        <div class="text-success small" style="font-size:0.7rem;">Verified Member</div>
      </div>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-outline-danger btn-sm px-3">Logout</button>
      </form>
    </div>
  </div>
</nav>

<div class="container-fluid">
  <div class="row">
    <div class="col-md-2 p-0 sidebar d-none d-md-block">
      <div class="p-3 border-bottom">
        <div class="fw-bold small">{{ auth()->user()->name }}</div>
        <div class="text-secondary" style="font-size:0.75rem;">{{ ucfirst(auth()->user()->role) }} · Trust {{ auth()->user()->trust_score ?? '0.0' }} ★</div>
      </div>
      
      <div class="section-head">Overview</div>
      <a href="{{ route('member.dashboard') }}" class="btn {{ Route::is('member.dashboard') ? 'active-link' : '' }}">🏠 Dashboard</a>
      <a href="{{ route('member.tools.index') }}" class="btn {{ Route::is('member.tools.index') ? 'active-link' : '' }}">🔍 Browse Tools</a>
      <a href="{{ route('member.dashboard') }}?panel=reservations" class="btn">📅 My Reservations</a>
      <a href="{{ route('member.dashboard') }}?panel=messages" class="btn">💬 Messages</a>

      <div class="section-head">My Tools</div>
      <a href="{{ route('member.dashboard') }}?panel=mytools" class="btn">🔧 My Listed Tools</a>
      <a href="{{ route('member.dashboard') }}?panel=addtool" class="btn">➕ List a Tool</a>

      <div class="section-head">Account</div>
      <a href="{{ route('member.dashboard') }}?panel=trust" class="btn">⭐ Trust Score</a>
      <a href="{{ route('member.dashboard') }}?panel=membership" class="btn">🏅 Membership</a>
    </div>

    <div class="col-md-10 p-4">
      @yield('content')
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
