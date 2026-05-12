<<<<<<< HEAD
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Edit Profile – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); border: none; }
    .btn-primary { border-radius: 8px; font-weight: bold; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">3EDTAK</a>
    <a href="{{ route('member.dashboard') }}" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
  </div>
</nav>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card p-4">
        <h4 class="fw-bold mb-4">Edit Profile</h4>

        @if(session('message'))
          <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <form action="{{ route('member.profile.update', $user->id) }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label fw-bold small">Full Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required/>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Phone Number</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required/>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Address</label>
            <textarea name="address" class="form-control" rows="3">{{ old('address', $user->address) }}</textarea>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">
              National ID (KYC Verification)
              @if($user->is_verified)
                <span class="badge bg-success ms-2">✓ Verified</span>
              @else
                <span class="badge bg-secondary ms-2">Not Verified</span>
              @endif
            </label>
            <input type="text" name="national_id" class="form-control" value="{{ old('national_id', $user->national_id) }}" placeholder="14-digit National ID"/>
            <div class="form-text small">Unlock high-value tools by providing your National ID.</div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Membership Tier</label>
            <input type="text" class="form-control bg-light" value="{{ $user->membershipTier->name ?? 'None' }}" disabled/>
          </div>

          <button type="submit" class="btn btn-primary w-100">Save Changes</button>
        </form>

        <hr class="my-4"/>

        <h5 class="fw-bold mb-3">Change Password</h5>
        <form action="{{ route('member.profile.changePassword') }}" method="POST">
          @csrf
          @method('PUT')

          <div class="mb-3">
            <label class="form-label fw-bold small">Current Password</label>
            <input type="password" name="current_password" class="form-control" required/>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">New Password</label>
            <input type="password" name="new_password" class="form-control" required/>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Confirm New Password</label>
            <input type="password" name="new_password_confirmation" class="form-control" required/>
          </div>

          <button type="submit" class="btn btn-outline-primary w-100">Update Password</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

