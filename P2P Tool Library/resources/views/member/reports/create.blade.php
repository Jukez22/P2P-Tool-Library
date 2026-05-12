<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Report Damage – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #fff5f5; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-danger mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">3EDTAK (Damage Report)</a>
    <a href="{{ route('member.dashboard') }}" class="btn btn-outline-light btn-sm">Cancel</a>
  </div>
</nav>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card p-4 border-top border-danger border-4">
        <h4 class="fw-bold mb-2 text-danger">Declare Damage</h4>
        <p class="text-secondary small mb-4">Please be honest about any issues. This helps the owner and keeps our community safe.</p>
        
        <form action="{{ route('member.reports.store') }}" method="POST">
          @csrf
          <input type="hidden" name="reservation_id" value="{{ $reservation_id }}"/>
          <input type="hidden" name="reported_tool_id" value="{{ $tool_id }}"/>
          <input type="hidden" name="reported_user_id" value="{{ $reported_user_id }}"/>

          <div class="mb-3">
            <label class="form-label fw-bold small">Reason for Report</label>
            <select name="reason" class="form-select" required>
              <option value="damaged_tool">Damaged Tool</option>
              <option value="late_return">Late Return</option>
              <option value="no_show">No Show</option>
              <option value="fraud">Fraud / Scam</option>
              <option value="other">Other</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Description / Details</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Please provide specific details about what happened..." required minlength="10"></textarea>
          </div>

          <div class="alert alert-warning small">
            ⚠️ Note: Once submitted, the Librarian will review the case and may contact both parties.
          </div>

          <button type="submit" class="btn btn-danger w-100 py-2 fw-bold">Submit Report</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
