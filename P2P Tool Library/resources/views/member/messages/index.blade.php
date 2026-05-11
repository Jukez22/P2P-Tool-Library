<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Messages – 3EDTAK</title>
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
    <a href="{{ route('member.dashboard') }}" class="btn btn-outline-light btn-sm">Back to Dashboard</a>
  </div>
</nav>

<div class="container">
  <div class="row">
    <div class="col-md-4">
      <div class="card p-3">
        <h5 class="fw-bold mb-3">Your Conversations</h5>
        <div class="list-group list-group-flush">
          @forelse($contacts as $contact)
            <a href="{{ route('member.messages.show', $contact->id) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
              <div>
                <div class="fw-bold">{{ $contact->name }}</div>
                <small class="text-secondary">Click to view chat</small>
              </div>
            </a>
          @empty
            <div class="text-center text-muted p-4">No conversations yet.</div>
          @endforelse
        </div>
      </div>
    </div>
    <div class="col-md-8">
      <div class="card p-5 text-center bg-light">
        <h4 class="text-secondary">Select a conversation to start chatting</h4>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
