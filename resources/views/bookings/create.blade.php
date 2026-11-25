@extends('layouts.user')
@section('title', 'Đặt sân')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <h4>Đặt {{ $court->name }}</h4>
    <p class="text-muted">
      {{ $court->description }} &middot; {{ number_format($court->price_per_hour) }}đ / giờ &middot; Giờ hoạt động 06:00 - 22:00
    </p>
    <form method="POST" action="{{ route('user.book.store', $court) }}" class="card card-body">
      @csrf
      <div class="mb-3">
        <label class="form-label">Ngày đặt</label>
        <input type="date" name="booking_date" class="form-control"
               min="{{ now()->toDateString() }}" value="{{ old('booking_date', now()->toDateString()) }}">
        @error('booking_date') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Giờ bắt đầu</label>
        <select name="start_hour" class="form-select">
          @for ($h = 6; $h <= 21; $h++)
            <option value="{{ $h }}" @selected((int) old('start_hour', 8) === $h)>{{ sprintf('%02d:00', $h) }}</option>
          @endfor
        </select>
        @error('start_hour') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Số giờ chơi</label>
        <select name="hours" class="form-select">
          @for ($n = 1; $n <= 4; $n++)
            <option value="{{ $n }}" @selected((int) old('hours', 1) === $n)>{{ $n }} giờ</option>
          @endfor
        </select>
        @error('hours') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <button class="btn btn-success">Xác nhận đặt sân</button>
      <a href="{{ route('home') }}" class="btn btn-link">Quay lại</a>
    </form>
  </div>
</div>
@endsection
