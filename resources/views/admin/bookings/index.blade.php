@extends('layouts.admin')
@section('title', 'Đơn đặt sân')
@section('content')
<h4 class="mb-3">Đơn đặt sân</h4>
<div class="table-responsive">
<table class="table table-bordered bg-white">
  <thead>
    <tr><th>#</th><th>Khách hàng</th><th>Sân</th><th>Ngày</th><th>Giờ</th><th>Tổng tiền</th><th>Trạng thái</th><th></th></tr>
  </thead>
  <tbody>
  @forelse ($bookings as $b)
    <tr>
      <td>{{ $b->id }}</td>
      <td>{{ $b->user->name }}<br><small class="text-muted">{{ $b->user->email }} {{ $b->user->phone }}</small></td>
      <td>{{ $b->court->name }}</td>
      <td>{{ $b->booking_date->format('d/m/Y') }}</td>
      <td>{{ $b->timeRange() }}</td>
      <td>{{ number_format($b->total_price) }}đ</td>
      <td><span class="badge bg-{{ $b->statusClass() }}">{{ $b->statusLabel() }}</span></td>
      <td class="text-nowrap">
        @if ($b->status === 'pending')
          <form method="POST" action="{{ route('admin.bookings.confirm', $b) }}" class="d-inline">
            @csrf
            <button class="btn btn-sm btn-success">Xác nhận</button>
          </form>
        @endif
        @if ($b->status !== 'cancelled')
          <form method="POST" action="{{ route('admin.bookings.cancel', $b) }}" class="d-inline"
                onsubmit="return confirm('Hủy đơn này?')">
            @csrf
            <button class="btn btn-sm btn-outline-danger">Hủy</button>
          </form>
        @endif
      </td>
    </tr>
  @empty
    <tr><td colspan="8">Chưa có đơn nào.</td></tr>
  @endforelse
  </tbody>
</table>
</div>
@endsection
