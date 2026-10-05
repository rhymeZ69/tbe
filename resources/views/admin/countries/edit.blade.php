@extends('admin.layouts.app')

@section('title', 'Edit Country')
@section('page_title', 'Edit Country')
@section('page_subtitle', 'Update ' . $country->name)

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
      <h2>Country Details</h2>
      <p>Editing <strong>{{ $country->name }}</strong> ({{ $country->code }})</p>
    </div>
    <a href="{{ route('admin.countries.index') }}" class="btn-toolbar btn-toolbar--ghost">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4"><path d="M19 12H5M11 18l-6-6 6-6"/></svg>
      Back to list
    </a>
  </header>

  <form method="POST" action="{{ route('admin.countries.update', $country) }}" class="country-form">
    @csrf
    @method('PUT')
    @include('admin.countries._form', ['country' => $country])
  </form>
</section>

@endsection