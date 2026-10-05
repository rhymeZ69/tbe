@extends('errors.layout')

@section('title', '401 — Unauthorized')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <rect x="3" y="11" width="18" height="11" rx="2"/>
    <path d="M7 11V7a5 5 0 0110 0v4"/>
  </svg>
@endsection

@section('code', '401')

@section('heading', 'Authentication Required')

@section('message')
  You need to sign in to access this page.
@endsection

@section('secondary_action')
  <a href="{{ route('admin.login') ?? url('/') }}" class="err-btn err-btn--ghost">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
      <path d="M15 3h4a2 2 0 012 2v14a2 2 0 01-2 2h-4"/>
      <path d="M10 17l5-5-5-5M15 12H3"/>
    </svg>
    Sign In
  </a>
@endsection