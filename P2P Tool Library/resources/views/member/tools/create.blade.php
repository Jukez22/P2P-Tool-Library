<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>List a New Tool – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">3EDTAK</a>
    <a href="{{ route('member.dashboard') }}" class="btn btn-outline-light btn-sm">Cancel</a>
  </div>
</nav>

<div class="container mb-5">
  <div class="row justify-content-center">
    <div class="col-md-7">
      <div class="card p-4">
        <h4 class="fw-bold mb-4 text-primary">List a New Tool</h4>
        
        <form action="{{ route('member.tools.store') }}" method="POST">
          @csrf
          
          <div class="mb-3">
            <label class="form-label fw-bold small">Tool Title</label>
            <input type="text" name="title" class="form-control" placeholder="e.g. Bosch Professional Drill" required/>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small">Price per Day (EGP)</label>
              <input type="number" name="price" class="form-control" placeholder="0.00" required/>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small">Category</label>
              <select name="category_id" class="form-select" required>
                <option value="">Select Category</option>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Condition</label>
            <select name="condition_status" class="form-select" required>
              <option value="Excellent">Excellent</option>
              <option value="Good">Good</option>
              <option value="Fair">Fair</option>
              <option value="Needs Repair">Needs Repair</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Full Description</label>
            <textarea name="description" class="form-control" rows="5" placeholder="Describe the tool, its features, and any rules for usage..." required></textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">List Tool Now</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
