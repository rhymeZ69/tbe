@extends('errors.layout')

@section('title', '404 — Page Not Found')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <circle cx="11" cy="11" r="7"/>
    <path d="M21 21l-4.3-4.3"/>
    <path d="M8 11h.01M14 11h.01"/>
  </svg>
@endsection

@section('code', '404')

@section('heading', 'Page Not Found')

@section('message')
  The page you're looking for doesn't exist or has been moved. It might have been renamed, deleted, or the link may be broken.
@endsection