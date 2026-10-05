@extends('admin.layouts.app')

@section('title', 'Product Categories')
@section('page_title', 'Product Categories')
@section('page_subtitle', 'Manage the product lines shown on your website')

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

{{-- ================= STATS ================= --}}
<div class="nl-stat-grid">
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Total</span>
      <strong class="nl-stat__value">{{ $stats['total'] }}</strong>
    </div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Active</span>
      <strong class="nl-stat__value">{{ $stats['active'] }}</strong>
    </div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">Featured</span>
      <strong class="nl-stat__value">{{ $stats['featured'] }}</strong>
    </div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
    </div>
    <div>
      <span class="nl-stat__label">With Products</span>
      <strong class="nl-stat__value">{{ $stats['with_products'] }}</strong>
    </div>
  </div>
</div>

{{-- ================= PANEL ================= --}}
<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.categories.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search by name or slug…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ $filter === 'all'      ? 'selected' : '' }}>All Categories</option>
        <option value="active"   {{ $filter === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ $filter === 'inactive' ? 'selected' : '' }}>Hidden</option>
        <option value="featured" {{ $filter === 'featured' ? 'selected' : '' }}>Featured</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || $filter !== 'all')
        <a href="{{ route('admin.categories.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.categories.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Category
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($categories->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M4 4h16v6H4zM4 14h16v6H4z"/></svg>
      @if ($search || $filter !== 'all')
        <h3>No categories match your filters</h3>
        <p>Try a different search term or clear the filters.</p>
        <a href="{{ route('admin.categories.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No categories yet</h3>
        <p>Add your first product category to start organising your catalogue.</p>
        <a href="{{ route('admin.categories.create') }}" class="btn-navy-md">Add Category</a>
      @endif
    </div>
  @else

    {{-- Table --}}
    <div class="table-wrap">
      <table class="data-table category-table">
        <thead>
          <tr>
            <th style="width: 70px;">Preview</th>
            <th>Category</th>
            <th style="width: 130px;">Slug</th>
            <th style="width: 90px;" class="center">Products</th>
            <th style="width: 80px;" class="center">Sort</th>
            <th style="width: 100px;" class="center">Featured</th>
            <th style="width: 110px;" class="center">Status</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($categories as $category)
            <tr class="{{ ! $category->is_active ? 'row--muted' : '' }}">

              {{-- Preview chip --}}
              <td>
                <div class="cat-preview {{ $category->gradient_class ?: 'prod-top' }}">
                  <span>{{ strtoupper(substr($category->name, 0, 1)) }}</span>
                </div>
              </td>

              {{-- Name + tagline --}}
              <td>
                <div class="cat-name">
                  <strong>{{ $category->name }}</strong>
                  @if ($category->tagline)
                    <span class="cat-tagline">{{ $category->tagline }}</span>
                  @endif
                </div>
                @if ($category->short_description)
                  <p class="cat-desc">{{ Str::limit($category->short_description, 80) }}</p>
                @endif
              </td>

              {{-- Slug --}}
              <td>
                <code class="code-chip">{{ $category->slug }}</code>
              </td>

              {{-- Products count --}}
              <td class="center">
                <span class="count-badge">{{ $category->products_count }}</span>
              </td>

              {{-- Sort order --}}
              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $category->sort_order ?? 0 }}</span>
              </td>

              {{-- Featured toggle --}}
              <td class="center">
                <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" class="inline-form">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="field" value="is_featured">
                  <button type="submit"
                          class="pill-toggle {{ $category->is_featured ? 'on-gold' : 'off' }}"
                          title="{{ $category->is_featured ? 'Unfeature' : 'Mark as featured' }}">
                    {{ $category->is_featured ? '★' : '☆' }}
                  </button>
                </form>
              </td>

              {{-- Active toggle --}}
              <td class="center">
                <form method="POST" action="{{ route('admin.categories.toggle', $category) }}" class="inline-form">
                  @csrf
                  @method('PATCH')
                  <input type="hidden" name="field" value="is_active">
                  <button type="submit"
                          class="pill-toggle {{ $category->is_active ? 'on-green' : 'off' }}"
                          title="{{ $category->is_active ? 'Hide from website' : 'Show on website' }}">
                    {{ $category->is_active ? 'Active' : 'Hidden' }}
                  </button>
                </form>
              </td>

              {{-- Actions --}}
              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.categories.edit', $category) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.categories.destroy', $category) }}"
                        class="inline-form js-delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            title="Delete category"
                            data-item-name="{{ $category->name }}"
                            data-item-type="category">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 012-2h4a2 2 0 012 2v2M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                    </button>
                  </form>
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    @if ($categories->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $categories->firstItem() }}</b>–<b>{{ $categories->lastItem() }}</b>
          of <b>{{ $categories->total() }}</b> categories
        </div>
        <div class="nl-pagination__links">
          {{ $categories->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection