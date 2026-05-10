<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Register – ToolShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
</head>
<body class="bg-black py-4">

<div class="container" style="max-width:500px;">
  <div class="card bg-dark border-secondary">
    <div class="card-body p-4">

      <div class="text-center mb-3">
        <div class="fs-2">🔧</div>
        <h5 class="fw-bold">Create Account</h5>
      </div>

      <!-- Step indicator -->
      <div class="d-flex align-items-center justify-content-center gap-2 mb-4" id="stepBar">
        <span class="badge bg-primary rounded-circle" id="d1">1</span>
        <small class="text-secondary" id="l1">Account</small>
        <hr class="flex-grow-1 my-0 border-secondary" id="ln1"/>
        <span class="badge bg-secondary rounded-circle" id="d2">2</span>
        <small class="text-secondary" id="l2">Identity</small>
        <hr class="flex-grow-1 my-0 border-secondary" id="ln2"/>
        <span class="badge bg-secondary rounded-circle" id="d3">3</span>
        <small class="text-secondary" id="l3">Confirmation</small>
      </div>

      @if ($errors->any())
          <div class="alert alert-danger py-2 small">
              @foreach ($errors->all() as $error)
                  <div>{{ $error }}</div>
              @endforeach
          </div>
      @endif

      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        <input type="hidden" name="name" id="full_name">
        <input type="hidden" name="role" value="borrower"> <!-- Default role -->

        <!-- Step 1 -->
        <div id="s1">
          <div class="row g-2 mb-3">
            <div class="col-6"><label class="form-label">First Name</label><input type="text" id="first_name" class="form-control" placeholder="Ahmed" required/></div>
            <div class="col-6"><label class="form-label">Last Name</label><input type="text" id="last_name" class="form-control" placeholder="Hassan" required/></div>
          </div>
          <div class="mb-3"><label class="form-label">Email</label><input type="email" name="email" class="form-control" placeholder="ahmed@example.com" value="{{ old('email') }}" required/></div>
          <div class="mb-3"><label class="form-label">Phone</label><input type="tel" name="phone" class="form-control" placeholder="+20 1X XXXX XXXX" value="{{ old('phone') }}" required/></div>
          <div class="mb-3"><label class="form-label">Password</label><input type="password" name="password" class="form-control" placeholder="Min 8 characters" required/></div>
          <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required/></div>
          <button type="button" class="btn btn-primary w-100" onclick="go(2)">Next: Identity Verification</button>
          <p class="text-center mt-2 small text-secondary">Have an account? <a href="{{ route('login') }}">Log in</a></p>
        </div>

        <!-- Step 2 -->
        <div id="s2" style="display:none;">
          <p class="text-secondary small mb-3">Required for borrowing high-value tools. Your data is encrypted.</p>
          <div class="mb-3"><label class="form-label">Full Legal Name</label><input type="text" class="form-control" placeholder="Ahmed Mohamed Hassan"/></div>
          <div class="row g-2 mb-3">
            <div class="col-6"><label class="form-label">National ID</label><input type="text" class="form-control" placeholder="29XXXXXXXXXXXXXXX"/></div>
            <div class="col-6"><label class="form-label">Date of Birth</label><input type="date" class="form-control"/></div>
          </div>
          <div class="mb-3">
            <label class="form-label">ID Card – Front</label>
            <input type="file" class="form-control" accept="image/*"/>
          </div>
          <div class="mb-3">
            <label class="form-label">ID Card – Back</label>
            <input type="file" class="form-control" accept="image/*"/>
          </div>
          <div class="mb-3">
            <label class="form-label">Preferred Zone</label>
            <select name="address" class="form-select">
              <option value="Cairo - Maadi">Cairo – Maadi</option>
              <option value="Cairo - Nasr City">Cairo – Nasr City</option>
              <option value="Cairo - Heliopolis">Cairo – Heliopolis</option>
              <option value="Giza - Dokki">Giza – Dokki</option>
              <option value="Giza - 6th October">Giza – 6th October</option>
            </select>
          </div> 

          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="terms" required/>
            <label class="form-check-label small text-secondary" for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Community Guidelines</a></label>
          </div>
          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary w-50" onclick="go(1)">Back</button>
            <button type="submit" class="btn btn-primary w-50" onclick="prepareSubmit()">Create Account</button>
          </div>
        </div>
      </form>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let cur = 1;
  function go(n) {
    document.getElementById('s'+cur).style.display='none';
    document.getElementById('s'+n).style.display='block';
    for(let i=1;i<=3;i++){
      const d=document.getElementById('d'+i);
      if(!d) continue;
      d.className = i<=n ? 'badge bg-primary rounded-circle' : 'badge bg-secondary rounded-circle';
      if(i<n) d.textContent='✓'; else d.textContent=i;
    }
    cur=n;
  }

  function prepareSubmit() {
    const first = document.getElementById('first_name').value;
    const last = document.getElementById('last_name').value;
    document.getElementById('full_name').value = first + ' ' + last;
  }
</script>
</body>
</html>
