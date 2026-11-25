@extends('layouts.user')
@section('title', 'Đăng ký')
@section('content')
<div class="row justify-content-center">
  <div class="col-md-6">
    <h4 class="mb-3">Đăng ký tài khoản</h4>
    <form method="POST" action="{{ route('register.post') }}" class="card card-body">
      @csrf
      <div class="mb-3">
        <label class="form-label">Họ tên</label>
        <input name="name" class="form-control" value="{{ old('name') }}">
        @error('name') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
        @error('email') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Số điện thoại</label>
        <input name="phone" class="form-control" value="{{ old('phone') }}">
        @error('phone') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Mật khẩu (ít nhất 6 ký tự)</label>
        <input type="password" name="password" class="form-control">
        @error('password') <div class="text-danger">{{ $message }}</div> @enderror
      </div>
      <div class="mb-3">
        <label class="form-label">Nhập lại mật khẩu</label>
        <input type="password" name="password_confirmation" class="form-control">
      </div>
      <button class="btn btn-success">Đăng ký</button>
      <div class="mt-3">Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></div>
    </form>
  </div>
</div>
@endsection
