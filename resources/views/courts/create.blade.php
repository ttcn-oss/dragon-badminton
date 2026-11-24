@extends('layouts.app')
@section('content')
<h5>Thêm sân</h5>
<form method="POST" action="{{ url('/courts') }}">
  @csrf
  @include('courts._form')
  <button class="btn btn-success">Lưu</button>
  <a href="{{ url('/courts') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
