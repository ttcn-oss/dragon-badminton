<!doctype html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Dragon Badminton</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container py-4">
  <h3 class="mb-3">Dragon Badminton – Quản lý sân</h3>
  @if (session('ok'))
    <div class="alert alert-success">{{ session('ok') }}</div>
  @endif
  @yield('content')
</div>
</body>
</html>
