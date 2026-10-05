<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="theme-color" content="#050F26">
<title>Sign In — Three Brothers Enterprises</title>

<link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/logo.png') }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ asset('img/logo.png') }}">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="login-page">

  {{-- ================= LEFT PANEL ================= --}}
  <aside class="login-panel">
    <div class="login-panel__brand">
      <img src="{{ asset('img/logo.png') }}" alt="Three Brothers Enterprises">
      <div class="login-panel__brand-text">
        <strong>Three Brothers</strong>
        <span>Enterprises</span>
      </div>
    </div>

    <div class="login-panel__hero">
      <span class="login-panel__eyebrow">Admin Control Centre</span>
      <h2>Manage your <em>global export</em> operations from one place.</h2>
      <p>
        Products, enquiries, video tours, markets and site content — everything you need to run
        Three Brothers Enterprises, in one secure dashboard.
      </p>

      <ul class="login-panel__list">
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
          Track quote enquiries in real time
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
          Manage products, images &amp; specs
        </li>
        <li>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
          Update site content anytime
        </li>
      </ul>
    </div>

    <p class="login-panel__foot">
      © {{ date('Y') }} Three Brothers Enterprises · <b>Pakistan → GCC &amp; Worldwide</b>
    </p>
  </aside>

  {{-- ================= RIGHT FORM ================= --}}
  <main class="login-form-wrap">
    <div class="login-form">

      {{-- Mobile-only brand --}}
      <div class="login-form__brand">
        <img src="{{ asset('img/logo.png') }}" alt="Three Brothers Enterprises">
        <div class="login-panel__brand-text">
          <strong style="color:#0A1F44">Three Brothers</strong>
          <span>Enterprises</span>
        </div>
      </div>

      <header class="login-form__header">
        <h1>Welcome</h1>
        <p>Sign in to access your admin dashboard.</p>
      </header>

      {{-- Flash: logout message --}}
      @if (session('status'))
        <div class="login-flash login-flash--success">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <path d="M20 6L9 17l-5-5"/>
          </svg>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      {{-- Flash: validation errors --}}
      @if ($errors->any())
        <div class="login-flash login-flash--error">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
            <circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/>
          </svg>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.login.attempt') }}" novalidate>
        @csrf

        {{-- Login: email or username --}}
        <div class="login-field">
          <label for="login">Email or Username</label>
          <div class="input-wrap">
            <input
              type="text"
              id="login"
              name="login"
              value="{{ old('login') }}"
              placeholder="you@threebrothers.com"
              required
              autofocus
              autocomplete="username"
              class="{{ $errors->has('login') ? 'is-invalid' : '' }}">

            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/>
              <circle cx="12" cy="7" r="4"/>
            </svg>
          </div>
        </div>

        {{-- Password --}}
        <div class="login-field">
          <label for="password">Password</label>
          <div class="input-wrap">
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              required
              autocomplete="current-password"
              class="{{ $errors->has('password') ? 'is-invalid' : '' }}">

            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="4" y="11" width="16" height="10" rx="2"/>
              <path d="M8 11V7a4 4 0 118 0v4"/>
            </svg>

            <button type="button" class="toggle-password" id="togglePassword" aria-label="Show password">
              <svg id="eyeIcon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                <circle cx="12" cy="12" r="3"/>
              </svg>
            </button>
          </div>
        </div>

        {{-- Remember me + Forgot password --}}
        <div class="login-row">
          <label class="remember-check">
            <input type="checkbox" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
            <span class="box">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5">
                <path d="M20 6L9 17l-5-5"/>
              </svg>
            </span>
            Remember me
          </label>

          <a href="{{ route('admin.password.request') ?? '#' }}" class="forgot-link">
            Forgot password?
          </a>
        </div>

        {{-- Submit --}}
        <button type="submit" class="login-submit" id="submitBtn">
          <span id="btnText">Sign In</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
            <path d="M5 12h14M13 6l6 6-6 6"/>
          </svg>
        </button>
      </form>

      <div class="login-divider">Authorised Access Only</div>

      <p class="login-note">
        This area is restricted to Three Brothers Enterprises staff.<br>
        Need access? <a href="mailto:exports@threebrothers.com">Contact the administrator</a>.
      </p>

    </div>
  </main>

</div>

<script>
/* ---------- Password visibility toggle ---------- */
const toggleBtn = document.getElementById('togglePassword');
const password  = document.getElementById('password');
const eyeIcon   = document.getElementById('eyeIcon');

const EYE_OPEN = `
  <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
  <circle cx="12" cy="12" r="3"/>
`;
const EYE_CLOSED = `
  <path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/>
  <path d="M1 1l22 22"/>
`;

toggleBtn.addEventListener('click', () => {
  const isPassword = password.type === 'password';
  password.type = isPassword ? 'text' : 'password';
  eyeIcon.innerHTML = isPassword ? EYE_CLOSED : EYE_OPEN;
  toggleBtn.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
});

/* ---------- Loading state on submit ---------- */
document.querySelector('form').addEventListener('submit', () => {
  const btn = document.getElementById('submitBtn');
  const txt = document.getElementById('btnText');
  txt.textContent = 'Signing in…';
  btn.disabled = true;
});
</script>

</body>
</html>