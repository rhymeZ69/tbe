@extends('errors.layout')

@section('title', '419 — Session Expired')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <circle cx="12" cy="12" r="9"/>
    <path d="M12 7v5l3 2"/>
  </svg>
@endsection

@section('code', '419')

@section('heading', 'Page Expired')

@section('message')
  Your session has expired for security reasons. This usually happens when you leave a page open for a long time. Please go back and try again.
@endsection

@section('secondary_action')
  <a href="javascript:history.back()" class="err-btn err-btn--ghost">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M19 12H5M11 18l-6-6 6-6"/>
    </svg>
    Go Back
  </a>
@endsection