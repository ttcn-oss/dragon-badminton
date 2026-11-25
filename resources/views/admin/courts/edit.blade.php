@extends('layouts.admin')
@section('title', 'Sửa sân')
@section('content')
<h4 class="mb-3">Sửa sân</h4>
<form method="POST" action="{{ route('admin.courts.update', $court) }}" class="card card-body" style="max-width: 600px">
  @csrf @method('PUT')
  @include('admin.courts._form')
  <div>
    <button class="btn btn-success">Cập nhật</button>
    <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary">Hủy</a>
  </div>
</form>
@endsection
