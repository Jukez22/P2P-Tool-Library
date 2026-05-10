<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Login – ToolShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #000; }
    .card { border-radius: 15px; }
    .nav-tabs .nav-link { color: #6c757d; border: none; }
    .nav-tabs .nav-link.active { 
        color: #fff; 
        background-color: transparent; 
        border-bottom: 2px solid #0d6efd; 
    }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card bg-dark border-secondary shadow-lg" style="width:100%; max-width:400px;">
  <div class="card-body p-4">
    <div class="text-center mb-4">
      <div class="fs-2">🔧</div>
      <h5 class="fw-bold mt-1">ToolShare</h5>
      <p class="small text-secondary">Select your portal to continue</p>
    </div>

    <ul class="nav nav-tabs nav-justified mb-4" id="roleTabs">
      <li class="nav-item">
        <button class="nav-link active small fw-bold" onclick="setRole(this,'member')">Member</button>
      </li>
      <li class="nav-item">
        <button class="nav-link small fw-bold" onclick="setRole(this,'librarian')">Librarian</button>
      </li>
      <li class="nav-item">
        <button class="nav-link small fw-bold" onclick="setRole(this,'technician')">Maintenance</button>
      </li>
    </ul>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="role" id="selectedRole" value="member">

        <div class="mb-3">
          <label class="form-label small">Email Address</label>
          <input type="email" name="email" class="form-control form-control-sm bg-black border-secondary text-white" id="email" placeholder="name@university.edu" value="{{ old('email') }}" required/>
        </div>
        <div class="mb-3">
          <label class="form-label small">Password</label>
          <input type="password" name="password" class="form-control form-control-sm bg-black border-secondary text-white" id="pass" placeholder="••••••••" required/>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rem"/>
            <label class="form-check-label small text-secondary" for="rem">Remember me</label>
          </div>
          <a href="#" class="small text-decoration-none">Forgot?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold">Sign In</button>
    </form>

    <p class="text-center mt-4 small text-secondary">
        New to ToolShare? <a href="{{ route('register') }}" class="text-primary text-decoration-none">Create Account</a>
    </p>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function setRole(element, roleValue) {
    document.getElementById('selectedRole').value = roleValue;
    document.querySelectorAll('#roleTabs .nav-link').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
  }
</script>
</body>
</html>
