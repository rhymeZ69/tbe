@extends('admin.layouts.app')

@section('title', 'Account Settings')
@section('page_title', 'Account Settings')
@section('page_subtitle', 'Manage your profile and password')

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

<div class="acc-layout">

  {{-- ================= SIDEBAR ================= --}}
  <aside class="acc-side">

    <div class="acc-side__inner">

      {{-- Profile summary --}}
      <div class="acc-hero">
        @if ($user->avatar)
          <div class="acc-hero__avatar" style="background-image:url('{{ asset($user->avatar) }}')"></div>
        @else
          <div class="acc-hero__avatar acc-hero__avatar--initials">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
        @endif

        <h3 class="acc-hero__name">{{ $user->name }}</h3>
        <p class="acc-hero__email">{{ $user->email }}</p>
        <span class="acc-hero__badge">{{ ucfirst($user->role ?? 'User') }}</span>
      </div>

      {{-- Section links --}}
      <nav class="acc-menu">
        <a href="#section-profile"  class="acc-menu__item">Profile Information</a>
        <a href="#section-avatar"   class="acc-menu__item">Avatar</a>
        <a href="#section-password" class="acc-menu__item">Change Password</a>
        <a href="#section-details"  class="acc-menu__item">Account Details</a>
      </nav>

      {{-- Account facts --}}
      <div class="acc-facts">
        <div class="acc-facts__row">
          <span class="acc-facts__label">Member Since</span>
          <span class="acc-facts__value">{{ $user->created_at->format('M j, Y') }}</span>
        </div>
        @if ($user->last_login_at)
          <div class="acc-facts__row">
            <span class="acc-facts__label">Last Login</span>
            <span class="acc-facts__value">{{ $user->last_login_at->diffForHumans() }}</span>
          </div>
        @endif
      </div>

    </div>

  </aside>

  {{-- ================= FORMS ================= --}}
  <div class="acc-main">

    {{-- ================= PROFILE ================= --}}
    <section class="acc-panel" id="section-profile">
      <header class="acc-panel__head">
        <h2>Profile Information</h2>
        <p>Your name, email, and contact details.</p>
      </header>

      <form method="POST" action="{{ route('admin.account.profile') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="acc-form-grid">

          <div class="acc-field acc-field--full">
            <label for="name">Full Name <span class="acc-req">*</span></label>
            <input type="text" id="name" name="name"
                   value="{{ old('name', $user->name) }}"
                   required maxlength="120"
                   class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
            @error('name') <span class="acc-error">{{ $message }}</span> @enderror
          </div>

          <div class="acc-field">
            <label for="email">Email Address <span class="acc-req">*</span></label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   required maxlength="255"
                   class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
            @error('email') <span class="acc-error">{{ $message }}</span> @enderror
          </div>

          <div class="acc-field">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone" name="phone"
                   value="{{ old('phone', $user->phone) }}"
                   placeholder="+92 300 000 0000"
                   maxlength="40">
          </div>

          <div class="acc-field acc-field--full">
            <label>Role</label>
            <div class="acc-readonly">{{ ucfirst($user->role ?? 'User') }}</div>
            <span class="acc-hint">Roles can only be changed by an administrator.</span>
          </div>

        </div>

        <div class="acc-actions">
          <button type="submit" class="acc-btn acc-btn--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
            Save Profile
          </button>
        </div>
      </form>
    </section>

    {{-- ================= AVATAR ================= --}}
    <section class="acc-panel" id="section-avatar">
      <header class="acc-panel__head">
        <h2>Avatar</h2>
        <p>Upload a profile photo. If none is set, your initial is used.</p>
      </header>

      <form method="POST" action="{{ route('admin.account.profile') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Preserve other fields so this form doesn't wipe them out --}}
        <input type="hidden" name="name"  value="{{ $user->name }}">
        <input type="hidden" name="email" value="{{ $user->email }}">
        <input type="hidden" name="phone" value="{{ $user->phone }}">

        <div class="acc-avatar-block">

          <div class="acc-avatar-preview">
            @if ($user->avatar)
              <div class="acc-avatar-preview__img" style="background-image:url('{{ asset($user->avatar) }}')" id="currentAvatar"></div>
            @else
              <div class="acc-avatar-preview__img acc-avatar-preview__img--initials" id="currentAvatar">
                {{ strtoupper(substr($user->name, 0, 1)) }}
              </div>
            @endif

            <div class="acc-avatar-preview__img acc-avatar-preview__img--new" id="avatarPreview" hidden></div>
          </div>

          <div class="acc-avatar-fields">

            <div class="acc-field">
              <label for="avatar_file">Upload new image</label>
              <input type="file" id="avatar_file" name="avatar_file"
                     accept="image/jpeg,image/png,image/webp"
                     class="acc-file {{ $errors->has('avatar_file') ? 'is-invalid' : '' }}">
              <span class="acc-hint">JPG, PNG or WebP · max 2 MB · square recommended</span>
              @error('avatar_file') <span class="acc-error">{{ $message }}</span> @enderror
            </div>

            @if ($user->avatar)
              <label class="acc-check">
                <input type="hidden" name="remove_avatar" value="0">
                <input type="checkbox" name="remove_avatar" value="1" class="acc-check__input">
                <span class="acc-check__box">
                  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
                </span>
                <span class="acc-check__label">
                  <strong>Remove current avatar</strong>
                  <em>Revert to the initial letter instead</em>
                </span>
              </label>
            @endif

          </div>

        </div>

        <div class="acc-actions">
          <button type="submit" class="acc-btn acc-btn--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
            Save Avatar
          </button>
        </div>
      </form>
    </section>

    {{-- ================= PASSWORD ================= --}}
    <section class="acc-panel" id="section-password">
      <header class="acc-panel__head">
        <h2>Change Password</h2>
        <p>Use a strong password with at least 8 characters.</p>
      </header>

      @if ($errors->has('current_password') || $errors->has('password'))
        <div class="acc-alert">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
          <span>{{ $errors->first('current_password') ?: $errors->first('password') }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('admin.account.password') }}">
        @csrf
        @method('PUT')

        <div class="acc-form-grid">

          <div class="acc-field acc-field--full">
            <label for="current_password">Current Password <span class="acc-req">*</span></label>
            <div class="acc-pw">
              <input type="password" id="current_password" name="current_password"
                     required autocomplete="current-password"
                     class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}">
              <button type="button" class="acc-pw__toggle" data-target="current_password" aria-label="Show password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <div class="acc-field">
            <label for="password">New Password <span class="acc-req">*</span></label>
            <div class="acc-pw">
              <input type="password" id="password" name="password"
                     required autocomplete="new-password" minlength="8"
                     class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
              <button type="button" class="acc-pw__toggle" data-target="password" aria-label="Show password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

          <div class="acc-field">
            <label for="password_confirmation">Confirm New Password <span class="acc-req">*</span></label>
            <div class="acc-pw">
              <input type="password" id="password_confirmation" name="password_confirmation"
                     required autocomplete="new-password" minlength="8">
              <button type="button" class="acc-pw__toggle" data-target="password_confirmation" aria-label="Show password">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
              </button>
            </div>
          </div>

        </div>

        <div class="acc-strength" id="passwordStrength" hidden>
          <div class="acc-strength__bars">
            <span></span><span></span><span></span><span></span>
          </div>
          <span class="acc-strength__label" id="passwordStrengthLabel"></span>
        </div>

        <div class="acc-actions">
          <button type="submit" class="acc-btn acc-btn--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
            Update Password
          </button>
        </div>
      </form>
    </section>

    {{-- ================= ACCOUNT DETAILS ================= --}}
    <section class="acc-panel" id="section-details">
      <header class="acc-panel__head">
        <h2>Account Details</h2>
        <p>Read-only information about your account.</p>
      </header>

      <dl class="acc-details">
        <div class="acc-details__row">
          <dt>User ID</dt>
          <dd>#{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</dd>
        </div>

        <div class="acc-details__row">
          <dt>Role</dt>
          <dd>
            <span class="acc-badge acc-badge--{{ $user->role === 'admin' ? 'green' : 'blue' }}">
              {{ ucfirst($user->role ?? 'User') }}
            </span>
          </dd>
        </div>

        <div class="acc-details__row">
          <dt>Status</dt>
          <dd>
            <span class="acc-badge acc-badge--{{ $user->is_active ? 'green' : 'red' }}">
              {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
          </dd>
        </div>

        <div class="acc-details__row">
          <dt>Member Since</dt>
          <dd>{{ $user->created_at->format('F j, Y') }}</dd>
        </div>

        @if ($user->last_login_at)
          <div class="acc-details__row">
            <dt>Last Login</dt>
            <dd>{{ $user->last_login_at->format('F j, Y \a\t g:i A') }}</dd>
          </div>
        @endif

        @if ($user->email_verified_at)
          <div class="acc-details__row">
            <dt>Email Verified</dt>
            <dd>{{ $user->email_verified_at->format('F j, Y') }}</dd>
          </div>
        @endif
      </dl>

      <div class="acc-danger">
        <div class="acc-danger__copy">
          <strong>Sign out of this device</strong>
          <span>End your session on this browser. You'll need to sign in again.</span>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
          @csrf
          <button type="submit" class="acc-btn acc-btn--danger">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2">
              <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/>
              <path d="M16 17l5-5-5-5M21 12H9"/>
            </svg>
            Sign Out
          </button>
        </form>
      </div>
    </section>

  </div>

</div>

@endsection

@push('scripts')
<script>
(function () {

  /* Avatar preview */
  const avatarInput   = document.getElementById('avatar_file');
  const avatarPreview = document.getElementById('avatarPreview');
  const currentAvatar = document.getElementById('currentAvatar');

  if (avatarInput && avatarPreview) {
    avatarInput.addEventListener('change', (e) => {
      const file = e.target.files[0];
      if (! file) {
        avatarPreview.hidden = true;
        if (currentAvatar) currentAvatar.hidden = false;
        return;
      }

      const reader = new FileReader();
      reader.onload = (ev) => {
        avatarPreview.style.backgroundImage = `url('${ev.target.result}')`;
        avatarPreview.hidden = false;
        if (currentAvatar) currentAvatar.hidden = true;
      };
      reader.readAsDataURL(file);
    });
  }

  /* Password visibility toggles */
  document.querySelectorAll('.acc-pw__toggle').forEach(btn => {
    btn.addEventListener('click', () => {
      const input = document.getElementById(btn.dataset.target);
      if (! input) return;

      const isPassword = input.type === 'password';
      input.type = isPassword ? 'text' : 'password';

      btn.innerHTML = isPassword
        ? '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19"/><path d="M1 1l22 22"/></svg>'
        : '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>';
    });
  });

  /* Password strength */
  const pwInput       = document.getElementById('password');
  const strengthWrap  = document.getElementById('passwordStrength');
  const strengthLabel = document.getElementById('passwordStrengthLabel');
  const strengthBars  = strengthWrap?.querySelectorAll('.acc-strength__bars span');

  function scorePassword(pw) {
    let score = 0;
    if (! pw) return 0;
    if (pw.length >= 8)  score++;
    if (pw.length >= 12) score++;
    if (/[A-Z]/.test(pw) && /[a-z]/.test(pw)) score++;
    if (/\d/.test(pw) && /[^A-Za-z0-9]/.test(pw)) score++;
    return Math.min(score, 4);
  }

  if (pwInput && strengthWrap) {
    pwInput.addEventListener('input', () => {
      const val = pwInput.value;
      if (! val) { strengthWrap.hidden = true; return; }

      strengthWrap.hidden = false;
      const score  = scorePassword(val);
      const levels = ['Too weak', 'Weak', 'Fair', 'Good', 'Strong'];
      const colors = ['#DC2626', '#F59E0B', '#D4A017', '#22C55E', '#0E7A6E'];

      strengthLabel.textContent = levels[score];
      strengthLabel.style.color = colors[score];

      strengthBars.forEach((bar, i) => {
        bar.style.background = i < score ? colors[score] : 'rgba(10,31,68,.10)';
      });
    });
  }

  /* Smooth scroll */
  document.querySelectorAll('.acc-menu__item').forEach(link => {
    link.addEventListener('click', (e) => {
      e.preventDefault();
      const target = document.getElementById(link.getAttribute('href').substring(1));
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
})();
</script>
@endpush