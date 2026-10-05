@extends('admin.layouts.app')

@section('title', 'Products')
@section('page_title', 'Products')
@section('page_subtitle', 'Manage your catalogue')

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

{{-- Stats --}}
<div class="nl-stat-grid">
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
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
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2l3 7h7l-5.5 4.5L18 21l-6-4-6 4 1.5-7.5L2 9h7z"/></svg>
    </div>
    <div><span class="nl-stat__label">Featured</span><strong class="nl-stat__value">{{ $stats['featured'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="5" width="18" height="14" rx="2"/><circle cx="8.5" cy="10" r="1.5"/><path d="M21 15l-5-5L5 19"/></svg>
    </div>
    <div><span class="nl-stat__label">With Images</span><strong class="nl-stat__value">{{ $stats['with_images'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.products.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search products…">
      </div>

      <select name="category" class="nl-select" onchange="this.form.submit()">
        <option value="">All Categories</option>
        @foreach ($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
        @endforeach
      </select>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"      {{ request('filter') === 'all'      ? 'selected' : '' }}>All Statuses</option>
        <option value="active"   {{ request('filter') === 'active'   ? 'selected' : '' }}>Active</option>
        <option value="inactive" {{ request('filter') === 'inactive' ? 'selected' : '' }}>Hidden</option>
        <option value="featured" {{ request('filter') === 'featured' ? 'selected' : '' }}>Featured</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || request('category') || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.products.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.products.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Product
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($products->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M21 16V8a2 2 0 00-1-1.7l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.7l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"/></svg>
      @if ($search || request('category') || request('filter'))
        <h3>No products match your filters</h3>
        <p>Try a different search term or clear the filters.</p>
        <a href="{{ route('admin.products.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No products yet</h3>
        <p>Add your first product to start building your catalogue.</p>
        <a href="{{ route('admin.products.create') }}" class="btn-navy-md">Add Product</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table product-table">
        <thead>
          <tr>
            <th style="width: 70px;">Image</th>
            <th>Product</th>
            <th style="width: 140px;">Category</th>
            <th style="width: 90px;" class="center">Details</th>
            <th style="width: 70px;" class="center">Sort</th>
            <th style="width: 100px;" class="center">Featured</th>
            <th style="width: 100px;" class="center">Status</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($products as $product)
            @php
              $img = $product->images->firstWhere('is_primary', true) ?? $product->images->first();
              $imgUrl = $img?->url;
            @endphp
            <tr class="{{ ! $product->is_active ? 'row--muted' : '' }}">

              <td>
                @if ($imgUrl)
                  <div class="prod-thumb" style="background-image:url('{{ $imgUrl }}')"></div>
                @else
                  <div class="prod-thumb prod-thumb--empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>{{ $product->name }}</strong>
                  @if ($product->tagline)
                    <span class="cat-tagline">{{ $product->tagline }}</span>
                  @endif
                </div>
                @if ($product->short_description)
                  <p class="cat-desc">{{ Str::limit($product->short_description, 85) }}</p>
                @endif
              </td>

              <td>
                @if ($product->category)
                  <span class="count-badge" style="background: rgba(27,95,184,.10); color: var(--navy-600); border-color: rgba(27,95,184,.2);">
                    {{ $product->category->name }}
                  </span>
                @else
                  <span class="muted">—</span>
                @endif
              </td>

              <td class="center">
                <div class="detail-chips">
                  <span title="{{ $product->varieties_count }} varieties">🎯 {{ $product->varieties_count }}</span>
                  <span title="{{ $product->specs_count }} specs">⚙ {{ $product->specs_count }}</span>
                </div>
              </td>

              <td class="center">
                <span class="muted" style="font-weight: 700;">{{ $product->sort_order ?? 0 }}</span>
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_featured">
                  <button type="submit" class="pill-toggle {{ $product->is_featured ? 'on-gold' : 'off' }}">
                    {{ $product->is_featured ? '★' : '☆' }}
                  </button>
                </form>
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.products.toggle', $product) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_active">
                  <button type="submit" class="pill-toggle {{ $product->is_active ? 'on-green' : 'off' }}">
                    {{ $product->is_active ? 'Active' : 'Hidden' }}
                  </button>
                </form>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  <a href="{{ route('admin.products.edit', $product) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.products.destroy', $product) }}"
                        class="inline-form js-delete-form">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            data-item-name="{{ $product->name }}"
                            data-item-type="product">
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

    @if ($products->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $products->firstItem() }}</b>–<b>{{ $products->lastItem() }}</b>
          of <b>{{ $products->total() }}</b> products
        </div>
        <div class="nl-pagination__links">
          {{ $products->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection