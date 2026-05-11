<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Edit Tool – {{ $tool->title }}</title>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h4 class="fw-bold m-0 text-primary">Edit Tool Listing</h4>
          <form action="{{ route('member.tools.destroy', $tool->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this listing?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-link text-danger p-0 small">Delete Listing</button>
          </form>
        </div>
        
        <form action="{{ route('member.tools.update', $tool->id) }}" method="POST">
          @csrf
          @method('PUT')
          
          <div class="mb-3">
            <label class="form-label fw-bold small">Tool Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $tool->title) }}" required/>
          </div>

          <div class="row mb-3">
            <div class="col-md-6">
              <label class="form-label fw-bold small">Price per Day (EGP)</label>
              <input type="number" name="price" class="form-control" value="{{ old('price', $tool->price) }}" required/>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-bold small">Category</label>
              <select name="category_id" class="form-select" required>
                @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ $tool->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Condition</label>
            <select name="condition_status" class="form-select" required>
              <option value="Excellent" {{ $tool->condition_status == 'Excellent' ? 'selected' : '' }}>Excellent</option>
              <option value="Good" {{ $tool->condition_status == 'Good' ? 'selected' : '' }}>Good</option>
              <option value="Fair" {{ $tool->condition_status == 'Fair' ? 'selected' : '' }}>Fair</option>
              <option value="Needs Repair" {{ $tool->condition_status == 'Needs Repair' ? 'selected' : '' }}>Needs Repair</option>
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label fw-bold small">Full Description</label>
            <textarea name="description" class="form-control" rows="5" required>{{ old('description', $tool->description) }}</textarea>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-bold">Save Changes</button>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
