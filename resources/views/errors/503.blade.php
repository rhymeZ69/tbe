@extends('errors.layout')

@section('title', '503 — Under Maintenance')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <circle cx="12" cy="12" r="9"/>
    <path d="M12 7v5l3 2"/>
  </svg>
@endsection

@section('code', '503')

@section('heading', 'We\'ll Be Right Back')

@section('message')
  Our website is currently undergoing scheduled maintenance. We're making improvements to serve you better — please check back shortly.
@endsection

@section('secondary_action')
  <a href="{{ url('/') }}" class="err-btn err-btn--ghost">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M5 12h14M13 6l6 6-6 6"/>
    </svg>
    Retry
  </a>
@endsection