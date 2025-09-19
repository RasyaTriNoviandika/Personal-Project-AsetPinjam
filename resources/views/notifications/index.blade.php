@extends('layouts.app')

@section('content')
<div class="container">
    <h2>🔔 Notifikasi</h2>

    @if($notifications->count() > 0)
        <ul class="list-group mt-3">
            @foreach($notifications as $notif)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $notif['title'] }}</strong><br>
                        <small>{{ $notif['message'] }}</small><br>
                        <a href="{{ $notif['url'] }}" class="btn btn-sm btn-link">Lihat</a>
                    </div>
                    <span class="badge bg-{{ $notif['type'] }}">{{ $notif['time'] }}</span>
                </li>
            @endforeach
        </ul>
    @else
        <p class="mt-3">Tidak ada notifikasi.</p>
    @endif
</div>
@endsection
