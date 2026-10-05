@extends('errors.layout')

@section('title', '403 — Access Denied')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <rect x="4" y="11" width="16" height="10" rx="2"/>
    <path d="M8 11V7a4 4 0 118 0v4"/>
  </svg>
@endsection

@section('code', '403')

@section('heading', 'Access Denied')

@section('message')
  You don't have permission to access this page. If you believe this is an error, please contact the site administrator.
@endsection