<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title') · Expenses</title>
<style>
  :root { --bg: #f6f7f9; --card: #fff; --text: #1d2330; --muted: #6b7280; --line: #e5e7eb; --accent: #2563eb; --danger: #dc2626; }
  @media (prefers-color-scheme: dark) {
    :root { --bg: #111418; --card: #1a1f26; --text: #e6e9ef; --muted: #9aa3b2; --line: #2a313b; --accent: #5b8def; --danger: #f87171; }
  }
  * { box-sizing: border-box; }
  body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 20px;
         font: 15px/1.45 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--bg); color: var(--text); }
  .card { width: 100%; max-width: 360px; background: var(--card); border: 1px solid var(--line); border-radius: 10px; padding: 24px; }
  h1 { font-size: 20px; margin: 0 0 4px; }
  p.sub { color: var(--muted); margin: 0 0 16px; font-size: 14px; }
  label { display: block; font-size: 12px; color: var(--muted); margin: 12px 0 4px; }
  input { width: 100%; padding: 9px 10px; border: 1px solid var(--line); border-radius: 8px; background: var(--bg); font: inherit; color: inherit; }
  .check { display: flex; align-items: center; gap: 8px; margin-top: 12px; font-size: 14px; color: var(--text); }
  .check input { width: auto; }
  button { margin-top: 18px; width: 100%; padding: 10px; border-radius: 8px; border: 1px solid var(--accent); background: var(--accent); color: #fff; font: inherit; cursor: pointer; }
  .error { color: var(--danger); font-size: 13px; margin-top: 12px; }
</style>
</head>
<body>
  <main class="card">
    @yield('content')
  </main>
</body>
</html>
