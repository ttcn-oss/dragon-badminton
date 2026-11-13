@extends('layouts.app')
@section('content')
<h5>Thêm sân</h5>
<form method="POST" action="{{ route('courts.store') }}">
  @csrf
  @include('courts._form')
  <button class="btn btn-success">Lưu</button>
  <a href="{{ route('courts.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
