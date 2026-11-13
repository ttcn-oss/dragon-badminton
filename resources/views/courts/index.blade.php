@extends('layouts.app')
@section('content')
<a href="{{ route('courts.create') }}" class="btn btn-success mb-3">+ Thêm sân</a>
<table class="table table-bordered">
  <thead><tr><th>#</th><th>Tên</th><th>Mô tả</th><th>Trạng thái</th><th></th></tr></thead>
  <tbody>
  @forelse ($courts as $c)
    <tr>
      <td>{{ $c->id }}</td>
      <td>{{ $c->name }}</td>
      <td>{{ $c->description }}</td>
      <td>{{ $c->status }}</td>
      <td>
        <a href="{{ route('courts.edit', $c) }}" class="btn btn-sm btn-primary">Sửa</a>
        <form action="{{ route('courts.destroy', $c) }}" method="POST" class="d-inline"
              onsubmit="return confirm('Xóa sân này?')">
          @csrf @method('DELETE')
          <button class="btn btn-sm btn-danger">Xóa</button>
        </form>
      </td>
    </tr>
  @empty
    <tr><td colspan="5">Chưa có sân nào.</td></tr>
  @endforelse
  </tbody>
</table>
@endsection
