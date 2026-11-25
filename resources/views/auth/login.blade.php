@extends('layouts.user')
@section('title', 'Đăng nhập')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-5">
    <h4 class="mb-3">Đăng nhập</h4>
    <form method="POST" action="{{ route('login.post') }}" class="card card-body">
      @csrf
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu</label>
        <input type="password" name="password" class="form-control">
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="form-check mb-3">
        <input class="form-check-input" type="checkbox" name="remember" id="remember">
        <label class="form-check-label" for="remember">Ghi nhớ đăng nhập</label>
      </div>
      <button class="btn btn-success">Đăng nhập</button>
      <div class="mt-3">Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></div>
    </form>
  </div>
</div>
@endsection
