@extends('admin.layouts.app')

@section('title', 'Pages')
@section('page_title', 'Pages')
@section('page_subtitle', 'Manage static content pages')

@section('content')

@if (session('status'))
  <div class="flash flash--success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><path d="M20 6L9 17l-5-5"/></svg>
    <span>{{ session('status') }}</span>
  </div>
@endif

{{-- Stats --}}
<div class="nl-stat-grid">
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--blue">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
    </div>
    <div><span class="nl-stat__label">Total</span><strong class="nl-stat__value">{{ $stats['total'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--green">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
    </div>
    <div><span class="nl-stat__label">Published</span><strong class="nl-stat__value">{{ $stats['published'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--muted">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
    </div>
    <div><span class="nl-stat__label">Draft</span><strong class="nl-stat__value">{{ $stats['draft'] }}</strong></div>
  </div>
  <div class="nl-stat">
    <div class="nl-stat__icon nl-stat__icon--gold">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 2"/></svg>
    </div>
    <div><span class="nl-stat__label">Scheduled</span><strong class="nl-stat__value">{{ $stats['scheduled'] }}</strong></div>
  </div>
</div>

<section class="panel">

  {{-- Toolbar --}}
  <div class="nl-toolbar">
    <form method="GET" action="{{ route('admin.pages.index') }}" class="nl-toolbar__form">
      <div class="nl-search">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="M21 21l-4.3-4.3"/></svg>
        <input type="search" name="q" value="{{ $search }}" placeholder="Search pages…">
      </div>

      <select name="filter" class="nl-select" onchange="this.form.submit()">
        <option value="all"       {{ request('filter') === 'all'       ? 'selected' : '' }}>All Pages</option>
        <option value="published" {{ request('filter') === 'published' ? 'selected' : '' }}>Published</option>
        <option value="draft"     {{ request('filter') === 'draft'     ? 'selected' : '' }}>Drafts</option>
        <option value="scheduled" {{ request('filter') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
      </select>

      <button type="submit" class="btn-toolbar">Apply</button>

      @if ($search || (request('filter') && request('filter') !== 'all'))
        <a href="{{ route('admin.pages.index') }}" class="btn-toolbar btn-toolbar--ghost">Reset</a>
      @endif
    </form>

    <div class="nl-toolbar__actions">
      <a href="{{ route('admin.pages.create') }}" class="btn-toolbar btn-toolbar--gold">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M12 5v14M5 12h14"/></svg>
        Add Page
      </a>
    </div>
  </div>

  {{-- Empty --}}
  @if ($pages->isEmpty())
    <div class="nl-empty">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
      @if ($search || request('filter'))
        <h3>No pages match your filters</h3>
        <p>Try a different search or clear the filters.</p>
        <a href="{{ route('admin.pages.index') }}" class="btn-navy-md">Clear Filters</a>
      @else
        <h3>No pages yet</h3>
        <p>Add your first page — e.g. About Us, Terms, Privacy Policy.</p>
        <a href="{{ route('admin.pages.create') }}" class="btn-navy-md">Add Page</a>
      @endif
    </div>
  @else

    <div class="table-wrap">
      <table class="data-table pages-table">
        <thead>
          <tr>
            <th style="width: 80px;">Preview</th>
            <th>Page</th>
            <th style="width: 160px;">Slug</th>
            <th style="width: 130px;">Updated</th>
            <th style="width: 140px;">Status</th>
            <th style="width: 90px;" class="center">Published</th>
            <th style="width: 90px; text-align: right;">Actions</th>
          </tr>
        </thead>
        <tbody>
          @foreach ($pages as $page)
            @php
              $isScheduled = $page->is_published && $page->published_at && $page->published_at->isFuture();
            @endphp
            <tr class="{{ ! $page->is_published ? 'row--muted' : '' }}">

              <td>
                @if ($page->featured_image)
                  <div class="page-thumb" style="background-image:url('{{ asset($page->featured_image) }}')"></div>
                @else
                  <div class="page-thumb page-thumb--empty">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><path d="M14 2v6h6"/></svg>
                  </div>
                @endif
              </td>

              <td>
                <div class="cat-name">
                  <strong>{{ $page->title }}</strong>
                </div>
                @if ($page->excerpt)
                  <p class="cat-desc">{{ Str::limit($page->excerpt, 100) }}</p>
                @endif
              </td>

              <td>
                <code class="code-chip">/{{ $page->slug }}</code>
              </td>

              <td class="muted">
                {{ $page->updated_at->format('M j, Y') }}
                <br>
                <span style="font-size: .72rem;">{{ $page->updated_at->diffForHumans() }}</span>
              </td>

              <td>
                @if ($page->is_published)
                  @if ($isScheduled)
                    <span class="status status--quoted">
                      <span class="dot"></span> Scheduled
                    </span>
                    <div class="cell-secondary" style="margin-top: 4px;">
                      Goes live {{ $page->published_at->diffForHumans() }}
                    </div>
                  @else
                    <span class="status status--won">
                      <span class="dot dot--green"></span> Published
                    </span>
                    @if ($page->published_at)
                      <div class="cell-secondary" style="margin-top: 4px;">
                        {{ $page->published_at->format('M j, Y') }}
                      </div>
                    @endif
                  @endif
                @else
                  <span class="status status--spam">
                    <span class="dot dot--muted"></span> Draft
                  </span>
                @endif
              </td>

              <td class="center">
                <form method="POST" action="{{ route('admin.pages.toggle', $page) }}" class="inline-form">
                  @csrf @method('PATCH')
                  <input type="hidden" name="field" value="is_published">
                  <button type="submit" class="pill-toggle {{ $page->is_published ? 'on-green' : 'off' }}"
                          title="{{ $page->is_published ? 'Move to drafts' : 'Publish' }}">
                    {{ $page->is_published ? 'Live' : 'Draft' }}
                  </button>
                </form>
              </td>

              <td style="text-align: right;">
                <div class="row-actions">
                  @if ($page->is_published && ! $isScheduled)
                    <a href="{{ url($page->slug) }}" target="_blank" class="row-btn" title="View page">
                      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"/><path d="M15 3h6v6M10 14L21 3"/></svg>
                    </a>
                  @endif

                  <a href="{{ route('admin.pages.edit', $page) }}" class="row-btn" title="Edit">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/><path d="M18.5 2.5a2.1 2.1 0 013 3L12 15l-4 1 1-4z"/></svg>
                  </a>

                  <form method="POST"
                        action="{{ route('admin.pages.destroy', $page) }}"
                        class="inline-form js-delete-form">
                    @csrf @method('DELETE')
                    <button type="button"
                            class="row-btn row-btn--danger js-delete-btn"
                            data-item-name="{{ $page->title }}"
                            data-item-type="page">
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

    @if ($pages->hasPages())
      <div class="nl-pagination">
        <div class="nl-pagination__info">
          Showing <b>{{ $pages->firstItem() }}</b>–<b>{{ $pages->lastItem() }}</b>
          of <b>{{ $pages->total() }}</b> pages
        </div>
        <div class="nl-pagination__links">
          {{ $pages->links('pagination::simple-default') }}
        </div>
      </div>
    @endif

  @endif

</section>

@endsection