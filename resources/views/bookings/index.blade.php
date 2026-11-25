@extends('layouts.user')
@section('title', 'Đơn của tôi')
@section('content')
<h4 class="mb-3">Đơn đặt sân của tôi</h4>
<div class="table-responsive">
<table class="table table-bordered bg-white">
  <thead>
    <tr><th>#</th><th>Sân</th><th>Ngày</th><th>Giờ</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr>
  </thead>
  <tbody>
  @forelse ($bookings as $b)
    <tr>
      <td>{{ $b->id }}</td>
      <td>{{ $b->court->name }}</td>
      <td>{{ $b->booking_date->format('d/m/Y') }}</td>
      <td>{{ $b->timeRange() }}</td>
      <td>{{ number_format($b->total_price) }}đ</td>
      <td><span class="badge bg-{{ $b->statusClass() }}">{{ $b->statusLabel() }}</span></td>
      <td>
        @if ($b->status !== 'cancelled' && $b->booking_date->gte(today()))
          <form method="POST" action="{{ route('user.bookings.cancel', $b) }}" onsubmit="return confirm('Hủy đơn này?')">
            @csrf
            <button class="btn btn-sm btn-outline-danger">Hủy đơn</button>
          </form>
        @endif
      </td>
    </tr>
  @empty
    <tr><td colspan="7">Bạn chưa có đơn đặt sân nào. <a href="{{ route('home') }}">Đặt sân ngay</a></td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
