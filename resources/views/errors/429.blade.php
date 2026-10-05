@extends('errors.layout')

@section('title', '429 — Too Many Requests')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <circle cx="12" cy="12" r="9"/>
    <path d="M12 8v5M12 16h.01"/>
  </svg>
@endsection

@section('code', '429')

@section('heading', 'Slow Down')

@section('message')
  You've made too many requests in a short period. Please wait a moment and try again.
@endsection