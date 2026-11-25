@extends('layouts.user')
@section('title', 'Danh sách sân')
@section('content')
<h4 class="mb-3">Danh sách sân</h4>
<div class="row g-3">
  @forelse ($courts as $c)
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h5 class="card-title">{{ $c->name }}</h5>
          <p class="card-text text-muted">{{ $c->description }}</p>
          <p class="fw-bold text-success mb-2">{{ number_format($c->price_per_hour) }}đ / giờ</p>
          <p class="small text-muted">Giờ hoạt động: 06:00 - 22:00</p>
        </div>
        <div class="card-footer bg-white">
          @auth
            <a href="{{ route('user.book.create', $c) }}" class="btn btn-success w-100">Đặt sân</a>
          @else
            <a href="{{ route('login') }}" class="btn btn-outline-success w-100">Đăng nhập để đặt sân</a>
          @endauth
        </div>
      </div>
    </div>
  @empty
    <p>Hiện chưa có sân nào hoạt động.</p>
  @endforelse
</div>
@endsection
