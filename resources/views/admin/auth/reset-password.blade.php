<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Reset Password — Three Brothers Enterprises</title>
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
      <span class="login-panel__eyebrow">Final Step</span>
      <h2>Choose a new <em>password</em>.</h2>
      <p>Make it strong — at least 8 characters, ideally a mix of letters, numbers and symbols.</p>
    </div>

    <p class="login-panel__foot">
      © {{ date('Y') }} Three Brothers Enterprises · <b>Pakistan → GCC &amp; Worldwide</b>
    </p>
  </aside>

  <main class="login-form-wrap">
    <div class="login-form">

      <header class="login-form__header">
        <h1>Set new password</h1>
        <p>Enter your email and choose a new password below.</p>
      </header>

      @if ($errors->any())
        <div class="login-flash login-flash--error">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
          <span>{{ $errors->first() }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.password.update') }}" novalidate>
        @csrf
        <input type="hidden" name="token" value="{{ $token }}">

        <div class="login-field">
          <label for="email">Email Address</label>
          <div class="input-wrap">
            <input type="email" id="email" name="email" value="{{ old('email', request('email')) }}"
                   placeholder="you@threebrothers.com" required autocomplete="email"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/>
            </svg>
          </div>
        </div>

        <div class="login-field">
          <label for="password">New Password</label>
          <div class="input-wrap">
            <input type="password" id="password" name="password" placeholder="At least 8 characters"
                   required autocomplete="new-password"
                   class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/>
            </svg>
          </div>
        </div>

        <div class="login-field">
          <label for="password_confirmation">Confirm New Password</label>
          <div class="input-wrap">
            <input type="password" id="password_confirmation" name="password_confirmation"
                   placeholder="Type it again" required autocomplete="new-password">
            <svg class="input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
              <rect x="4" y="11" width="16" height="10" rx="2"/><path d="M8 11V7a4 4 0 118 0v4"/>
            </svg>
          </div>
        </div>

        <button type="submit" class="login-submit">
          <span>Reset Password</span>
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4">
            <path d="M5 12h14M13 6l6 6-6 6"/>
          </svg>
        </button>
      </form>

      <div class="login-divider">or</div>

      <p class="login-note">
        <a href="{{ route('admin.login') }}">Back to sign in</a>
      </p>
    </div>
  </main>
</div>

</body>
</html>