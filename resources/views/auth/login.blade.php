@extends('layouts.app')

@section('content')
<!-- Login View (Empty scaffold) -->
<div class="container mx-auto">
    <h2>Login</h2>
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <!-- Form fields here -->
    </form>
</div>
@endsection
