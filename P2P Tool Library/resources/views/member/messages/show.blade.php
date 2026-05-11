<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1"/>
  <title>Chat with {{ $contact->name }} – 3EDTAK</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"/>
  <style>
    body { background-color: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .card { border-radius: 12px; border: none; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .chat-box { height: 400px; overflow-y: auto; padding: 20px; background: white; border-radius: 12px; }
    .msg { margin-bottom: 15px; max-width: 75%; padding: 10px 15px; border-radius: 15px; position: relative; }
    .msg-sent { background: #0d6efd; color: white; margin-left: auto; border-bottom-right-radius: 2px; }
    .msg-received { background: #e9ecef; color: #333; margin-right: auto; border-bottom-left-radius: 2px; }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
  <div class="container">
    <a class="navbar-brand fw-bold" href="{{ route('member.dashboard') }}">3EDTAK</a>
    <a href="{{ route('member.messages.index') }}" class="btn btn-outline-light btn-sm">Back to Inbox</a>
  </div>
</nav>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card p-3 mb-3">
        <h5 class="fw-bold mb-3 border-bottom pb-2">Chat with {{ $contact->name }}</h5>
        
        <div class="chat-box mb-3 border">
          @forelse($messages as $msg)
            <div class="msg {{ $msg->sender_id == auth()->id() ? 'msg-sent' : 'msg-received' }}">
              {{ $msg->content }}
              <div style="font-size: 0.65rem; opacity: 0.7; margin-top: 5px;">
                {{ $msg->created_at->format('H:i') }}
              </div>
            </div>
          @empty
            <div class="text-center text-muted p-5">No messages yet. Start the conversation!</div>
          @endforelse
        </div>

        <form action="{{ route('member.messages.store') }}" method="POST">
          @csrf
          <input type="hidden" name="receiver_id" value="{{ $contact->id }}"/>
          <div class="input-group">
            <input type="text" name="content" class="form-control" placeholder="Type your message..." required/>
            <button class="btn btn-primary" type="submit">Send</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  // Auto scroll to bottom of chat
  const chatBox = document.querySelector('.chat-box');
  chatBox.scrollTop = chatBox.scrollHeight;
</script>
</body>
</html>
