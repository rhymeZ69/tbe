@extends('admin.layouts.app')

@section('title', 'Add Video Stage')
@section('page_title', 'Add Video Stage')
@section('page_subtitle', 'Create a new infrastructure stage for the homepage')

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
      <h2>Stage Details</h2>
      <p>Fields marked with <span style="color:#B91C1C;">*</span> are required.</p>
    </div>
    <a href="{{ route('admin.video-tours.index') }}" class="btn-toolbar btn-toolbar--ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Back to list
    </a>
  </header>

  <form method="POST" action="{{ route('admin.video-tours.store') }}" enctype="multipart/form-data" class="video-tour-form">
    @csrf
    @include('admin.video-tours._form', ['tour' => null, 'nextStage' => $nextStage])
  </form>
</section>

@endsection