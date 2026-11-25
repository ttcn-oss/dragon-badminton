<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Quản trị') - Dragon Badminton</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
  <div class="row">
    <nav class="col-12 col-md-2 bg-dark text-white p-3 min-vh-100">
      <h5 class="mb-4">Quản trị</h5>
      <div class="d-flex flex-column gap-2">
        <a class="text-white text-decoration-none" href="{{ route('admin.dashboard') }}">Tổng quan</a>
        <a class="text-white text-decoration-none" href="{{ route('admin.courts.index') }}">Quản lý sân</a>
        <a class="text-white text-decoration-none" href="{{ route('admin.bookings.index') }}">Đơn đặt sân</a>
        <hr class="border-secondary">
        <a class="text-white-50 text-decoration-none" href="{{ route('home') }}">Về trang người dùng</a>
        <span class="text-white-50 small">{{ auth()->user()->name }}</span>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="btn btn-outline-light btn-sm">Đăng xuất</button>
        </form>
      </div>
    </nav>
    <main class="col-12 col-md-10 p-4 bg-light">
      @if (session('ok'))
        <div class="alert alert-success">{{ session('ok') }}</div>
      @endif
      @yield('content')
    </main>
  </div>
</div>
</body>
</html>
