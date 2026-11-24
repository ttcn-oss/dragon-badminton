@extends('layouts.app')
@section('content')
<h5>Sửa sân</h5>
<form method="POST" action="{{ url('/courts/'.$court->id) }}">
  @csrf @method('PUT')
  @include('courts._form')
  <button class="btn btn-success">Cập nhật</button>
  <a href="{{ url('/courts') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
