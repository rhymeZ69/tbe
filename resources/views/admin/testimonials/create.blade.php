@extends('admin.layouts.app')

@section('title', 'Add Testimonial')
@section('page_title', 'Add Testimonial')
@section('page_subtitle', 'Create a new client review')

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
      <h2>Testimonial Details</h2>
      <p>Fields marked with <span style="color:#B91C1C;">*</span> are required.</p>
    </div>
    <a href="{{ route('admin.testimonials.index') }}" class="btn-toolbar btn-toolbar--ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Back to list
    </a>
  </header>

  <form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" class="testimonial-form">
    @csrf
    @include('admin.testimonials._form', ['testimonial' => null])
  </form>
</section>

@endsection