@extends('admin.layouts.app')

@section('title', 'Add Page')
@section('page_title', 'Add Page')
@section('page_subtitle', 'Create a new content page')

@section('content')

@if ($errors->any())
  <div class="flash flash--error">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2"><circle cx="12" cy="12" r="10"/><path d="M12 8v5M12 16h.01"/></svg>
    <span>Please check the form below — {{ $errors->count() }} {{ Str::plural('issue', $errors->count()) }} found.</span>
  </div>
@endif

<section class="panel">
  <header class="panel__head">
    <div>
      <h2>Page Details</h2>
      <p>Fields marked with <span style="color:#B91C1C;">*</span> are required.</p>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="btn-toolbar btn-toolbar--ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Back to list
    </a>
  </header>

  <form method="POST" action="{{ route('admin.pages.store') }}" enctype="multipart/form-data" class="page-form">
    @csrf
    @include('admin.pages._form', ['page' => null])
  </form>
</section>

@endsection