@extends('errors.layout')

@section('title', '405 — Method Not Allowed')

@section('icon')
  <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
    <circle cx="12" cy="12" r="9"/>
    <path d="M5 12h14"/>
  </svg>
@endsection

@section('code', '405')

@section('heading', 'Method Not Allowed')

@section('message')
  The request method used isn't supported for this page. Please go back and try a different action.
@endsection