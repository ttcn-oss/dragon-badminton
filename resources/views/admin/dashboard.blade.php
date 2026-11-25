@extends('layouts.admin')
@section('title', 'Tổng quan')
@section('content')
<h4 class="mb-4">Tổng quan</h4>
<div class="row g-3">
  <div class="col-md-4"><div class="card text-center"><div class="card-body">
    <div class="text-muted">Số sân</div><div class="fs-2 fw-bold">{{ $stats['courts'] }}</div></div></div></div>
  <div class="col-md-4"><div class="card text-center"><div class="card-body">
    <div class="text-muted">Khách hàng</div><div class="fs-2 fw-bold">{{ $stats['users'] }}</div></div></div></div>
  <div class="col-md-4"><div class="card text-center"><div class="card-body">
    <div class="text-muted">Đơn hôm nay</div><div class="fs-2 fw-bold">{{ $stats['today'] }}</div></div></div></div>
  <div class="col-md-6"><div class="card text-center"><div class="card-body">
    <div class="text-muted">Đơn chờ xác nhận</div><div class="fs-2 fw-bold text-warning">{{ $stats['pending'] }}</div></div></div></div>
  <div class="col-md-6"><div class="card text-center"><div class="card-body">
    <div class="text-muted">Doanh thu (đơn đã xác nhận)</div><div class="fs-2 fw-bold text-success">{{ number_format($stats['revenue']) }}đ</div></div></div></div>
</div>
@endsection
