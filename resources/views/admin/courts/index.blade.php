@extends('layouts.admin')
@section('title', 'Quản lý sân')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h4 class="mb-0">Quản lý sân</h4>
  <a href="{{ route('admin.courts.create') }}" class="btn btn-success">+ Thêm sân</a>
</div>
<table class="table table-bordered bg-white">
  <thead><tr><th>#</th><th>Tên</th><th>Mô tả</th><th>Giá / giờ</th><th>Trạng thái</th><th></th></tr></thead>
  <tbody>
  @forelse ($courts as $c)
    <tr>
      <td>{{ $c->id }}</td>
      <td>{{ $c->name }}</td>
      <td>{{ $c->description }}</td>
      <td>{{ number_format($c->price_per_hour) }}đ</td>
      <td>{{ $c->status === 'active' ? 'Hoạt động' : 'Bảo trì' }}</td>
      <td>
        <a href="{{ route('admin.courts.edit', $c) }}" class="btn btn-sm btn-primary">Sửa</a>
        <form action="{{ route('admin.courts.destroy', $c) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Xóa sân này? Các đơn đặt của sân cũng bị xóa.')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Xóa</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="6">Chưa có sân nào.</td></tr>
  @endforelse
  </tbody>
</table>
@endsection
