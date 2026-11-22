@extends('auth.layout')
@section('title', 'Create your account')
@section('content')
  <h1>Create your account</h1>
  <p class="sub">This is a one-time step. After this account exists, sign-up is closed.</p>
  <form method="POST" action="{{ route('setup') }}">
    @csrf
    <label for="name">Name</label>
    <input id="name" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="username">
    <label for="password">Password (at least 8 characters)</label>
    <input id="password" name="password" type="password" required autocomplete="new-password">
    <label for="password_confirmation">Confirm password</label>
    <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
    @if ($errors->any())
      <div class="error" role="alert">{{ $errors->first() }}</div>
    @endif
    <button type="submit">Create account</button>
  </form>
@endsection
