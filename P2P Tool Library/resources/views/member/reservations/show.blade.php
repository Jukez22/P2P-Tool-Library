@extends('layouts.app')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto; text-align: center;">
    <h1 style="margin-bottom: 1rem; font-weight: 800;">Reservation Details</h1>
    
    <div style="margin-bottom: 2rem; padding: 1.5rem; background: rgba(255,255,255,0.02); border-radius: 16px;">
        <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Tool: <span style="color: white; font-weight: 600;">{{ $reservation->tool->name }}</span></p>
        <p style="color: var(--text-muted); margin-bottom: 0.5rem;">Status: <span style="padding: 4px 12px; border-radius: 20px; background: rgba(99,102,241,0.2); color: #818cf8; font-size: 0.8rem;">{{ $reservation->status }}</span></p>
        <p style="color: var(--text-muted);">Dates: <span style="color: white;">{{ $reservation->start_datetime->format('M d, Y') }} - {{ $reservation->end_datetime->format('M d, Y') }}</span></p>
    </div>

    @if($reservation->handoverVerification)
        <div style="margin-top: 2rem;">
            <h2 style="font-size: 1.25rem; margin-bottom: 1rem;">Handover QR Code</h2>
            <div style="background: white; display: inline-block; padding: 20px; border-radius: 20px; margin-bottom: 1rem;">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ $reservation->handoverVerification->qr_code }}" 
                     alt="QR Code" 
                     style="display: block;">
            </div>
            <p style="color: var(--text-muted); font-size: 0.9rem;">
                Show this QR code to the tool owner during the handover to verify the transaction.
            </p>
        </div>
    @else
        <p style="color: #fca5a5;">Verification code not available for this reservation.</p>
    @endif

    <div style="margin-top: 2rem; border-top: 1px solid var(--glass-border); padding-top: 1.5rem;">
        <a href="{{ route('member.reservations.index') }}" class="btn btn-primary">Back to Reservations</a>
    </div>
</div>
@endsection
