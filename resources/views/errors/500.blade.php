@extends('errors.layout')

@section('title', '500 — Server Error')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <path d="M12 2l8 4v6c0 5-3.4 8.8-8 10-4.6-1.2-8-5-8-10V6z"/>
    <path d="M12 8v5M12 16h.01"/>
  </svg>
@endsection

@section('code', '500')

@section('heading', 'Something Went Wrong')

@section('message')
  We're experiencing a temporary technical issue on our side. Our team has been notified — please try again in a few minutes.
@endsection

@section('secondary_action')
  <a href="javascript:location.reload()" class="err-btn err-btn--ghost">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M23 4v6h-6M1 20v-6h6"/>
      <path d="M3.5 9a9 9 0 0114.9-3.4L23 10M1 14l4.6 4.4A9 9 0 0020.5 15"/>
    </svg>
    Try Again
  </a>
@endsection