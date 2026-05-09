@extends('layouts.app')

@section('content')
<!-- Register View (Empty scaffold) -->
<div class="container mx-auto">
    <h2>Register</h2>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Form fields here -->
    </form>
</div>
@endsection
