@extends('admin.layouts.app')

@section('title', 'Manage Users')
@section('page_title', 'Manage Users')
@section('page_subtitle', 'Accounts with access to the admin panel')

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

@if ($errors->any())
  <div class="flash flash--error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
    <span>{{ $errors->first() }}</span>
  </div>
@endif

<div class="nl-stat-grid">
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
    </div>
    <div><span class="nl-stat__label">Total</span><strong class="nl-stat__value">{{ $stats['total'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div><span class="nl-stat__label">Active</span><strong class="nl-stat__value">{{ $stats['active'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/></svg>
    </div>
    <div><span class="nl-stat__label">Admins</span><strong class="nl-stat__value">{{ $stats['admins'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
    </div>
    <div><span class="nl-stat__label">Editors</span><strong class="nl-stat__value">{{ $stats['editors'] }}</strong></div>
  </div>
</div>

<section class="panel">

  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.users.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search name, email, phone…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ request('filter') === 'all'      ? 'selected' : '' }}>All Users</option>
        <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Inactive</option>
        <option value="admin"    {{ request('filter') === 'admin'    ? 'selected' : '' }}>Admins</option>
        <option value="editor"   {{ request('filter') === 'editor'   ? 'selected' : '' }}>Editors</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.users.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.users.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add User
      </a>
    </div>
  </div>

  @if ($users->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
      @if ($search || request('filter'))
        <h3>No users match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.users.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No users yet</h3>
        <p>Add your first admin or editor to grant access to the dashboard.</p>
        <a href="{{ route('admin.users.create') }}" class="btn-navy-md">Add User</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table user-table">
        <thead>
          <tr>
            <th style="width: 70px;">Avatar</th>
            <th>User</th>
            <th style="width: 100px;">Role</th>
            <th style="width: 140px;">Last Login</th>
            <th style="width: 110px;" class="center">Status</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($users as $user)
            <tr class="{{ ! $user->is_active ? 'row--muted' : '' }}">

              <td>
                @if ($user->avatar)
                  <div class="user-thumb" style="background-image:url('{{ asset($user->avatar) }}')"></div>
                @else
                  <div class="user-thumb user-thumb--initials">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>
                    {{ $user->name }}
                    @if ($user->id === auth()->id())
                      <span class="you-badge">You</span>
                    @endif
                  </strong>
                </div>
                <p class="cat-desc">
                  <a href="mailto:{{ $user->email }}" class="muted-link">{{ $user->email }}</a>
                  @if ($user->phone)
                    · <span class="muted">{{ $user->phone }}</span>
                  @endif
                </p>
              </td>

              <td>
                <span class="role-badge role-badge--{{ $user->role }}">
                  {{ ucfirst($user->role) }}
                </span>
              </td>

              <td class="muted">
                @if ($user->last_login_at)
                  {{ $user->last_login_at->format('M j, Y') }}
                  <br>
                  <span style="font-size: .72rem;">{{ $user->last_login_at->diffForHumans() }}</span>
                @else
                  <span class="muted">Never</span>
                @endif
              </td>

              <td class="center">
                @if ($user->id === auth()->id())
                  <span class="pill-toggle on-green" style="cursor: not-allowed;" title="You cannot change your own status">
                    Active
                  </span>
                @else
                  <form method="POST" action="{{ route('admin.users.toggle', $user) }}" class="inline-form">
                    @csrf @method('PATCH')
                    <button type="submit" class="pill-toggle {{ $user->is_active ? 'on-green' : 'off' }}"
                            title="{{ $user->is_active ? 'Deactivate' : 'Activate' }}">
                      {{ $user->is_active ? 'Active' : 'Inactive' }}
                    </button>
                  </form>
                @endif
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.users.edit', $user) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  @if ($user->id !== auth()->id())
                    <form method="POST"
                          action="{{ route('admin.users.destroy', $user) }}"
                          class="inline-form js-delete-form">
                      @csrf @method('DELETE')
                      <button type="button"
                              class="row-btn row-btn--danger js-delete-btn"
                              data-item-name="{{ $user->name }}"
                              data-item-type="user">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                      </button>
                    </form>
                  @endif
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    @if ($users->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $users->firstItem() }}</b>–<b>{{ $users->lastItem() }}</b>
          of <b>{{ $users->total() }}</b> users
        </div>
        <div class="nl-pagination__links">
          {{ $users->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection