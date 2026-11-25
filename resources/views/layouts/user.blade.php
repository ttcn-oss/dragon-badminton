<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dragon Badminton')</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-dark bg-success mb-4">
  <div class="container d-flex flex-wrap gap-2">
    <a class="navbar-brand" href="{{ route('home') }}">Dragon Badminton</a>
    <div class="d-flex flex-wrap align-items-center gap-2">
      <a class="text-white text-decoration-none" href="{{ route('home') }}">Danh sách sân</a>
      @auth
        <a class="text-white text-decoration-none" href="{{ route('user.bookings') }}">Đơn của tôi</a>
        @if (auth()->user()->isAdmin())
          <a class="text-white text-decoration-none" href="{{ route('admin.dashboard') }}">Trang quản trị</a>
        @endif
        <span class="text-white-50">Xin chào, {{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}" class="d-inline">
          @csrf
          <button class="btn btn-outline-light btn-sm">Đăng xuất</button>
        </form>
      @else
        <a class="text-white text-decoration-none" href="{{ route('login') }}">Đăng nhập</a>
        <a class="btn btn-light btn-sm" href="{{ route('register') }}">Đăng ký</a>
      @endauth
    </div>
  </div>
</nav>
<div class="container pb-5">
  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @if (session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif
  @yield('content')
</div>
</body>
</html>
