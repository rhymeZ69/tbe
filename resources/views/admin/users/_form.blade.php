@php
  $isEdit = $user !== null;
  $val = fn ($field, $default = '') => old($field, $isEdit ? $user->{$field} : $default);
  $isSelf = $isEdit && $user->id === auth()->id();
@endphp

<div class="form-sections">

  {{-- ================= BASIC INFO ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Basic Information</h3>

    <div class="form-grid">
      <div class="field">
        <label for="name">Full Name <span class="req">*</span></label>
        <input type="text" id="name" name="name"
               value="{{ $val('name') }}"
               required maxlength="120"
               class="{{ $errors->has('name') ? 'is-invalid' : '' }}">
        @error('name') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="email">Email <span class="req">*</span></label>
        <input type="email" id="email" name="email"
               value="{{ $val('email') }}"
               required maxlength="255"
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
        @error('email') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="phone">Phone</label>
        <input type="tel" id="phone" name="phone"
               value="{{ $val('phone') }}"
               placeholder="+92 300 000 0000"
               maxlength="40">
      </div>

      <div class="field">
        <label for="role">Role <span class="req">*</span></label>
        <select id="role" name="role" required class="{{ $errors->has('role') ? 'is-invalid' : '' }}">
          <option value="editor" {{ $val('role') === 'editor' ? 'selected' : '' }}>Editor — limited access</option>
          <option value="admin"  {{ $val('role') === 'admin'  ? 'selected' : '' }}>Admin — full access</option>
        </select>
        <span class="field-hint">Admins can manage users and site settings. Editors manage content only.</span>
        @error('role') <span class="field-error">{{ $message }}</span> @enderror
      </div>
    </div>
  </div>

  {{-- ================= AVATAR ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Avatar</h3>
    <p class="form-section__hint">Optional. If no avatar is uploaded, the user's initial is shown instead.</p>

    @if ($isEdit && $user->avatar)
      <div class="existing-image">
        <div class="existing-image__thumb existing-image__thumb--square" style="background-image:url('{{ asset($user->avatar) }}')"></div>
        <div class="existing-image__meta">
          <strong>Current avatar</strong>
          <span>{{ $user->avatar }}</span>
          <label class="existing-image__remove">
            <input type="checkbox" name="remove_avatar" value="1">
            <span>Remove this avatar</span>
          </label>
        </div>
      </div>
    @endif

    <div class="field">
      <label for="avatar_file">{{ $isEdit && $user->avatar ? 'Replace avatar' : 'Upload avatar' }}</label>
      <input type="file" id="avatar_file" name="avatar_file"
             accept="image/jpeg,image/png,image/webp"
             class="image-file">
      <span class="field-hint">JPG, PNG, or WebP · max 2 MB · square recommended</span>
      @error('avatar_file') <span class="field-error">{{ $message }}</span> @enderror
    </div>
  </div>

  {{-- ================= PASSWORD ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">
      {{ $isEdit ? 'Reset Password' : 'Password' }}
    </h3>
    <p class="form-section__hint">
      @if ($isEdit)
        Leave blank to keep the current password. Fill in both fields to change it.
      @else
        Minimum 8 characters. The user can change this from their account settings later.
      @endif
    </p>

    <div class="form-grid">
      <div class="field">
        <label for="password">Password {{ $isEdit ? '' : '*' }}</label>
        <input type="password" id="password" name="password"
               {{ $isEdit ? '' : 'required' }}
               autocomplete="new-password" minlength="8"
               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
        @error('password') <span class="field-error">{{ $message }}</span> @enderror
      </div>

      <div class="field">
        <label for="password_confirmation">Confirm Password {{ $isEdit ? '' : '*' }}</label>
        <input type="password" id="password_confirmation" name="password_confirmation"
               {{ $isEdit ? '' : 'required' }}
               autocomplete="new-password" minlength="8">
      </div>
    </div>
  </div>

  {{-- ================= STATUS ================= --}}
  <div class="form-section">
    <h3 class="form-section__title">Status</h3>

    @if ($isSelf)
      <div class="flash flash--error" style="margin: 0 0 6px;">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
        <span>You cannot deactivate your own account.</span>
      </div>
    @endif

    <div class="checkbox-stack">
      <label class="checkbox-row {{ $isSelf ? 'is-disabled' : '' }}">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1"
               {{ $isSelf ? 'disabled' : '' }}
               {{ $val('is_active', $isEdit ? null : 1) ? 'checked' : '' }}>
        <span class="checkbox-row__box">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3.5"><path d="M20 6L9 17l-5-5"/></svg>
        </span>
        <div class="checkbox-row__text">
          <strong>Active</strong>
          <span>Allow this user to sign in to the admin panel</span>
        </div>
      </label>
    </div>
  </div>

  {{-- ================= ACTIONS ================= --}}
  <div class="form-actions">
    <a href="{{ route('admin.users.index') }}" class="btn-toolbar btn-toolbar--ghost">Cancel</a>
    <button type="submit" class="btn-navy-md">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M20 6L9 17l-5-5"/></svg>
      {{ $isEdit ? 'Save Changes' : 'Create User' }}
    </button>
  </div>

</div>