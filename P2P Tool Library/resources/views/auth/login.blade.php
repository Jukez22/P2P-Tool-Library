<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Login – ToolShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { 
      background-color: #f8f9fa; 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .card { 
      border-radius: 20px; 
      border: none;
      box-shadow: 0 10px 25px rgba(0,0,0,0.05) !important;
    }
    .nav-tabs .nav-link { 
      color: #6c757d; 
      border: none; 
      font-size: 0.85rem;
    }
    .nav-tabs .nav-link.active { 
      color: #0d6efd; 
      background-color: transparent; 
      border-bottom: 3px solid #0d6efd; 
      font-weight: bold;
    }
    .form-control { border-radius: 8px; border: 1px solid #dee2e6; }
    .btn-primary { border-radius: 8px; background-color: #0d6efd; border: none; }
  </style>
</head>
<body class="d-flex align-items-center justify-content-center" style="min-height:100vh;">

<div class="card bg-white shadow-lg" style="width:100%; max-width:420px;">
  <div class="card-body p-4 p-md-5">
    <div class="text-center mb-4">
      <div class="fs-1">🛠️</div>
      <h4 class="fw-bold text-dark">Portal Login</h4>
      <p class="small text-muted">Select your role to access your dashboard</p>
    </div>

    <ul class="nav nav-tabs nav-justified mb-4" id="roleTabs">
      <li class="nav-item">
        <button class="nav-link active fw-bold" onclick="setRole(this,'member')">Member</button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-bold" onclick="setRole(this,'librarian')">Librarian</button>
      </li>
      <li class="nav-item">
        <button class="nav-link fw-bold" onclick="setRole(this,'technician')">Maintenance</button>
      </li>
    </ul>

    @if ($errors->any())
        <div class="alert alert-danger py-2 small mb-3">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf
        <input type="hidden" name="role" id="selectedRole" value="member">

        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Email Address</label>
          <input type="email" name="email" class="form-control" id="email" placeholder="name@university.edu" value="{{ old('email') }}" required/>
        </div>
        <div class="mb-3">
          <label class="form-label small fw-bold text-secondary">Password</label>
          <input type="password" name="password" class="form-control" id="pass" placeholder="••••••••" required/>
        </div>
        
        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check">
            <input class="form-check-input" type="checkbox" name="remember" id="rem"/>
            <label class="form-check-label small text-secondary" for="rem">Remember me</label>
          </div>
          <a href="#" class="small text-decoration-none">Forgot?</a>
        </div>

        <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm py-2">Sign In</button>
        
        <p class="text-center mt-3 small text-secondary">
            Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register Now</a>
        </p>
        <p class="text-center mt-2 small text-secondary">
            Return to homepage? <a href="{{ url('/') }}" class="text-decoration-none"> Home</a>
        </p>
    </form>
  </div>
</div>

<script>
  function setRole(element, roleValue) {
    document.getElementById('selectedRole').value = roleValue;
    document.querySelectorAll('#roleTabs .nav-link').forEach(btn => btn.classList.remove('active'));
    element.classList.add('active');
    console.log("Current role selected:", roleValue);
  }
</script>

</body>
</html>
