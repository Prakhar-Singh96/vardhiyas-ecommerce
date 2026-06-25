{{-- resources/views/admin/auth/login.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Login — Vardiyash</title>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <!-- Bootstrap 5 -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Bootstrap Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

  <style>
    * { font-family: 'Public Sans', sans-serif; }

    body {
      background-color: #f4f5fb;
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .card {
      border: none;
      border-radius: 0.5rem;
      box-shadow: 0 2px 6px 0 rgba(67,89,113,.12);
    }

    .app-brand {
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 8px;
      text-decoration: none;
      margin-bottom: 1.5rem;
    }

    .app-brand-text {
      font-size: 1.5rem;
      font-weight: 700;
      color: #696cff;
      letter-spacing: -0.5px;
    }

    .app-brand-logo {
      width: 36px;
      height: 36px;
      background: #696cff;
      border-radius: 8px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.1rem;
    }

    .form-label {
      font-size: 0.875rem;
      font-weight: 500;
      color: #566a7f;
    }

    .form-control {
      border-color: #d9dee3;
      border-radius: 0.375rem;
      padding: 0.5rem 0.875rem;
      font-size: 0.9375rem;
      color: #697a8d;
    }

    .form-control:focus {
      border-color: #696cff;
      box-shadow: 0 0 0 0.2rem rgba(105,108,255,.15);
    }

    .input-group-text {
      background: white;
      border-color: #d9dee3;
      color: #697a8d;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #696cff;
      border-color: #696cff;
      font-weight: 500;
      padding: 0.5rem 1.25rem;
      border-radius: 0.375rem;
      font-size: 0.9375rem;
    }

    .btn-primary:hover {
      background-color: #5f61e6;
      border-color: #5f61e6;
    }

    .form-check-input:checked {
      background-color: #696cff;
      border-color: #696cff;
    }

    .divider-text {
      color: #a1acb8;
      font-size: 0.8125rem;
    }

    h5.card-title {
      font-size: 1.25rem;
      font-weight: 600;
      color: #566a7f;
    }

    .text-muted-custom {
      color: #a1acb8;
      font-size: 0.875rem;
    }
  </style>
</head>
<body>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5 col-sm-8 col-11">

        <!-- Brand -->
        <a href="#" class="app-brand">
          <div class="app-brand-logo">
            <i class="bi bi-shop"></i>
          </div>
          <span class="app-brand-text">Vardiyash</span>
        </a>

        <!-- Card -->
        <div class="card p-4">
          <div class="card-body">

            <h5 class="card-title mb-1">Welcome to Vardiyash! 👋</h5>
            <p class="text-muted-custom mb-4">Please sign-in to your admin account</p>

            {{-- Error Alert --}}
            @if ($errors->any())
              <div class="alert alert-danger d-flex align-items-center gap-2 py-2" role="alert">
                <i class="bi bi-exclamation-triangle-fill"></i>
                <span>{{ $errors->first() }}</span>
              </div>
            @endif

            {{-- Success Message --}}
            @if (session('status'))
              <div class="alert alert-success py-2">{{ session('status') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}">
              @csrf

              <!-- Email -->
              <div class="mb-3">
                <label class="form-label" for="email">Email</label>
                <input
                  type="email"
                  id="email"
                  name="email"
                  class="form-control @error('email') is-invalid @enderror"
                  value="{{ old('email') }}"
                  placeholder="admin@vardiyash.com"
                  autofocus
                  required
                >
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>

              <!-- Password -->
              <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center">
                  <label class="form-label" for="password">Password</label>
                </div>
                <div class="input-group">
                  <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control @error('password') is-invalid @enderror"
                    placeholder="············"
                    required
                  >
                  <span class="input-group-text" onclick="togglePassword()">
                    <i class="bi bi-eye" id="toggleIcon"></i>
                  </span>
                  @error('password')
                    <div class="invalid-feedback">{{ $message }}</div>
                  @enderror
                </div>
              </div>

              <!-- Remember Me -->
              <div class="mb-4">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" name="remember" id="remember">
                  <label class="form-check-label text-muted-custom" for="remember">
                    Remember Me
                  </label>
                </div>
              </div>

              <!-- Submit -->
              <button type="submit" class="btn btn-primary d-grid w-100">
                Sign In
              </button>

            </form>

          </div>
        </div>
        <!-- /Card -->

        <p class="text-center text-muted-custom mt-3">
          <small>Vardiyash Admin Panel &copy; {{ date('Y') }}</small>
        </p>

      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script>
    function togglePassword() {
      const input = document.getElementById('password');
      const icon  = document.getElementById('toggleIcon');
      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
      }
    }
  </script>

</body>
</html>