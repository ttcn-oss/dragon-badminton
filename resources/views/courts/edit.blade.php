@extends('layouts.app')
@section('content')
<h5>Sửa sân</h5>
<form method="POST" action="{{ route('courts.update', $court) }}">
  @csrf @method('PUT')
  @include('courts._form')
  <button class="btn btn-success">Cập nhật</button>
  <a href="{{ route('courts.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection
