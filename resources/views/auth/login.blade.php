@extends('auth.layout')
@section('title', 'Sign in')
@section('content')
  <h1>Expenses</h1>
  <p class="sub">Sign in to continue.</p>
  <form method="POST" action="{{ route('login') }}">
    @csrf
    <label for="email">Email</label>
    <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus autocomplete="username">
    <label for="password">Password</label>
    <input id="password" name="password" type="password" required autocomplete="current-password">
    <label class="check"><input type="checkbox" name="remember" value="1" checked> Keep me signed in</label>
    @if ($errors->any())
      <div class="error" role="alert">{{ $errors->first() }}</div>
    @endif
    <button type="submit">Sign in</button>
  </form>
@endsection
