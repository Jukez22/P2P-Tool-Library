<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Register – ToolShare</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 15px; border: 1px solid #dee2e6; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .role-card { cursor: pointer; transition: 0.3s; border: 2px solid #dee2e6; }
    .role-card:hover { border-color: #0d6efd; background-color: #f0f7ff; }
    .role-card.selected { border-color: #0d6efd; background-color: #e7f1ff; position: relative; }
    .role-card.selected::after { content: '✓'; position: absolute; top: 10px; right: 10px; color: #0d6efd; font-weight: bold; }
    .step-badge { width: 30px; height: 30px; display: inline-flex; align-items: center; justify-content: center; font-weight: bold; }
  </style>
</head>
<body class="py-5">

<div class="container" style="max-width:550px;">
  <div class="card bg-white">
    <div class="card-body p-4">

      <div class="text-center mb-4">
        <div class="fs-1">🔧</div>
        <h4 class="fw-bold">Join ToolShare</h4>
        <p class="text-secondary small">Start sharing and borrowing tools today</p>
      </div>

      <div class="d-flex align-items-center justify-content-center gap-2 mb-4" id="stepBar">
        <span class="badge rounded-circle bg-primary step-badge" id="d1">1</span>
        <small class="fw-bold text-primary" id="l1">Account</small>
        <hr class="flex-grow-1 border-secondary opacity-25" id="ln1"/>
        <span class="badge rounded-circle bg-secondary step-badge" id="d2">2</span>
        <small class="text-secondary" id="l2">Identity</small>
        <hr class="flex-grow-1 border-secondary opacity-25" id="ln2"/>
        <span class="badge rounded-circle bg-secondary step-badge" id="d3">3</span>
        <small class="text-secondary" id="l3">Done</small>
      </div>

      @if ($errors->any())
          <div class="alert alert-danger py-2 small mb-4">
              @foreach ($errors->all() as $error)
                  <div>{{ $error }}</div>
              @endforeach
          </div>
      @endif

      <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf
        <input type="hidden" name="name" id="full_name">
        <input type="hidden" name="role" id="user_role" value="borrower">

        <!-- Step 1 -->
        <div id="s1">
          <label class="form-label fw-bold small mb-2">I am joining as a:</label>
          <div class="row g-2 mb-4">
            <div class="col-4">
              <div class="role-card p-3 text-center rounded h-100 selected" id="role-borrower" onclick="selectRole('borrower')">
                <div class="fs-3">👤</div>
                <div class="small fw-bold">Member</div>
              </div>
            </div>
            <div class="col-4">
              <div class="role-card p-3 text-center rounded h-100" id="role-librarian" onclick="selectRole('librarian')">
                <div class="fs-3">🔑</div>
                <div class="small fw-bold">Librarian</div>
              </div>
            </div>
            <div class="col-4">
              <div class="role-card p-3 text-center rounded h-100" id="role-technician" onclick="selectRole('technician')">
                <div class="fs-3">🛠️</div>
                <div class="small fw-bold">Maintenance</div>
              </div>
            </div>
          </div>

          <div class="row g-2 mb-3">
            <div class="col-6"><label class="form-label small">First Name</label><input type="text" id="first_name" class="form-control" placeholder="Ahmed" required/></div>
            <div class="col-6"><label class="form-label small">Last Name</label><input type="text" id="last_name" class="form-control" placeholder="Hassan" required/></div>
          </div>
          <div class="mb-3">
            <label class="form-label small">Email</label>
            <input type="email" name="email" class="form-control" placeholder="ahmed@example.com" value="{{ old('email') }}" required/>
          </div>
          <div class="mb-3">
            <label class="form-label small">Phone</label>
            <input type="tel" name="phone" class="form-control" placeholder="+20 1X XXXX XXXX" value="{{ old('phone') }}" required/>
          </div>
          <div class="mb-3">
            <label class="form-label small">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Min 8 characters" required/>
          </div>
          <div class="mb-3">
            <label class="form-label small">Confirm Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" required/>
          </div>
          
          <button type="button" class="btn btn-primary w-100 py-2 fw-bold" onclick="go(2)">Next: Identity Verification</button>
          <p class="text-center mt-3 small text-secondary">Already have an account? <a href="{{ route('login') }}" class="text-decoration-none">Log in</a></p>
        </div>

        <!-- Step 2 -->
        <div id="s2" style="display:none;">
          <div class="alert alert-info py-2 small">Your identity is required to build trust in the community. Your data is encrypted.</div>
          

          
          <div class="row g-2 mb-3">
            <div class="col-6">
              <label class="form-label small">National ID Number</label>
              <input type="text" class="form-control" placeholder="29XXXXXXXXXXXX"/>
            </div>
      
          
          <div class="mb-3">
            <label class="form-label small">Upload National ID (Front)</label>
            <input type="file" class="form-control form-control-sm" accept="image/*"/>
          </div>

          <div class="mb-3">
            <label class="form-label small">Preferred Service Zone</label>
            <select name="address" class="form-select">
              <option value="Cairo - Maadi">Cairo – Maadi</option>
              <option value="Cairo - Nasr City">Cairo – Nasr City</option>
              <option value="Cairo - Heliopolis">Cairo – Heliopolis</option>
              <option value="Giza - Dokki">Giza – Dokki</option>
              <option value="Giza - 6th October">Giza – 6th October</option>
            </select>
          </div>

          <div class="form-check mb-4">
            <input class="form-check-input" type="checkbox" id="terms" required/>
            <label class="form-check-label small text-secondary" for="terms">I agree to the <a href="#">Terms</a> and <a href="#">Guidelines</a></label>
          </div>

          <div class="d-flex gap-2">
            <button type="button" class="btn btn-outline-secondary w-50" onclick="go(1)">Back</button>
            <button type="button" class="btn btn-primary w-50 fw-bold" onclick="submitRegistration()">Complete Registration</button>
          </div>
        </div>

        <!-- Step 4: Success State (Used if we want to show it before redirect or after success) -->
        <div id="s4" style="display:none;" class="text-center py-4">
          <div class="display-1">🎉</div>
          <h4 class="fw-bold mt-3">Welcome to ToolShare!</h4>
          <p class="text-secondary small px-4">Your account is ready. Our team will verify your documents shortly.</p>
          <div class="spinner-border text-primary mt-3" role="status">
            <span class="visually-hidden">Loading...</span>
          </div>
          <p class="mt-2 text-primary fw-bold">Redirecting you to your dashboard...</p>
        </div>
      </form>

<script>
  // Automatically prepare the full name before the form submits
  document.getElementById('registerForm').addEventListener('submit', function() {
    const first = document.getElementById('first_name').value;
    const last = document.getElementById('last_name').value;
    document.getElementById('full_name').value = first + ' ' + last;
  });
</script>

    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  let cur = 1;

  function selectRole(role) {
    document.getElementById('user_role').value = role;
    document.querySelectorAll('.role-card').forEach(card => card.classList.remove('selected'));
    document.getElementById('role-' + role).classList.add('selected');
    console.log("Selected role:", role);
  }

  function go(n) {
    if (n > cur) {
        // Validation for Step 1
        if (cur === 1) {
            const required = ['first_name', 'last_name', 'email', 'phone', 'password'];
            let valid = true;
            required.forEach(id => {
                const el = document.getElementById(id);
                if (el && !el.value) {
                    el.classList.add('is-invalid');
                    valid = false;
                } else if (el) {
                    el.classList.remove('is-invalid');
                }
            });
            if (!valid) return;
        }
        
        // Validation for Step 2 (Identity & Terms)
        if (cur === 2) {
            const terms = document.getElementById('terms');
            if (terms && !terms.checked) {
                alert('Please agree to the Terms and Guidelines to continue.');
                return;
            }
            // Check if National ID is filled (assuming it's the first input in s2)
            const nid = document.querySelector('#s2 input[type="text"]');
            if (nid && !nid.value) {
                nid.classList.add('is-invalid');
                return;
            }
        }
    }

    document.getElementById('s' + cur).style.display = 'none';
    document.getElementById('s' + n).style.display = 'block';

    for(let i=1; i<=3; i++) {
      const d = document.getElementById('d' + i);
      if(!d) continue;

      if(i < n || (n === 4 && i <= 3)) {
        d.className = 'badge rounded-circle bg-success step-badge text-white';
        d.textContent = '✓';
      } else if (i === n) {
        d.className = 'badge rounded-circle bg-primary step-badge text-white';
        d.textContent = i;
      } else {
        d.className = 'badge rounded-circle bg-secondary step-badge text-white';
        d.textContent = i;
      }
    }
    
    if(n === 4) document.getElementById('stepBar').classList.add('d-none');
    cur = n;
  }

  // Combines full name, then submits the form normally to Laravel
  function submitRegistration() {
    // Build full name from the two visible fields
    const first = document.getElementById('first_name').value.trim();
    const last  = document.getElementById('last_name').value.trim();
    if (!first || !last) {
        document.getElementById('first_name').classList.toggle('is-invalid', !first);
        document.getElementById('last_name').classList.toggle('is-invalid', !last);
        return;
    }
    document.getElementById('full_name').value = first + ' ' + last;

    const terms = document.getElementById('terms');
    if (!terms || !terms.checked) {
        alert('Please agree to the Terms and Guidelines to continue.');
        return;
    }

    // Show the success animation, then let the form submit
    go(4);
    document.getElementById('registerForm').submit();
  }


</script>
</body>
</html>
