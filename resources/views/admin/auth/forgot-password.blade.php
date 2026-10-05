<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Forgot Password — Three Brothers Enterprises</title>
<link rel="icon" type="image/png" href="{{ asset('img/logo.png') }}">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>
<body>

<div class="login-page">
  <aside class="login-panel">
    <div class="login-panel__brand">
      <img src="{{ asset('img/logo.png') }}" alt="Three Brothers Enterprises">
      <div class="login-panel__brand-text">
        <strong>Three Brothers</strong>
        <span>Enterprises</span>
      </div>
    </div>

    <div class="login-panel__hero">
      <span class="login-panel__eyebrow">Password Recovery</span>
      <h2>Forgot your <em>password?</em> It happens.</h2>
      <p>
        Enter the email address linked to your admin account and we'll send you a secure link
        to reset your password.
      </p>
    </div>

    <p class="login-panel__foot">
      © {{ date('Y') }} Three Brothers Enterprises · <b>Pakistan → GCC &amp; Worldwide</b>
    </p>
  </aside>

  <main class="login-form-wrap">
    <div class="login-form">

      <header class="login-form__header">
        <h1>Reset your password</h1>
        <p>We'll email you a link to create a new one.</p>
      </header>

      @if (session('status'))
        <div class="login-flash login-flash--success">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
          <span>{{ session('status') }}</span>
        </div>
      @endif

      @if ($errors->any())
        <div class="login-flash login-flash--error">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.password.email') }}" novalidate>
        @csrf

        <div class="login-field">
          <label for="email">Email Address</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" value="{{ old('email') }}"
                   placeholder="you@threebrothers.com" required autofocus autocomplete="email"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/>
            </svg>
          </div>
        </div>

        <button type="submit" class="login-submit">
          <span>Send Reset Link</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
            <path d="M5 12h14M13 6l6 6-6 6"/>
          </svg>
        </button>
      </form>

      <div class="login-divider">or</div>

      <p class="login-note">
        Remembered it? <a href="{{ route('admin.login') }}">Back to sign in</a>
      </p>
    </div>
  </main>
</div>

</body>
</html>