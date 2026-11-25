@extends('layouts.admin')
@section('title', 'Thêm sân')
@section('content')
<h4 class="mb-3">Thêm sân</h4>
<form method="POST" action="{{ route('admin.courts.store') }}" class="card card-body" style="max-width: 600px">
  @csrf
  @include('admin.courts._form')
  <div>
    <button class="btn btn-success">Lưu</button>
    <a href="{{ route('admin.courts.index') }}" class="btn btn-secondary">Hủy</a>
  </div>
</form>
@endsection
