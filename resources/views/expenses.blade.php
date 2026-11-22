<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Expenses</title>
<style>
  :root {
    --bg: #f6f7f9; --card: #fff; --text: #1d2330; --muted: #6b7280; --line: #e5e7eb;
    --accent: #2563eb; --danger: #dc2626; --radius: 10px;
  }
  @media (prefers-color-scheme: dark) {
    :root { --bg: #111418; --card: #1a1f26; --text: #e6e9ef; --muted: #9aa3b2; --line: #2a313b; --accent: #5b8def; --danger: #f87171; }
  }
  * { box-sizing: border-box; }
  body { margin: 0; font: 15px/1.45 system-ui, -apple-system, "Segoe UI", Roboto, sans-serif; background: var(--bg); color: var(--text); }
  header { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 16px 20px; max-width: 1100px; margin: 0 auto; flex-wrap: wrap; }
  header h1 { font-size: 20px; margin: 0; }
  main { max-width: 1100px; margin: 0 auto; padding: 0 20px 40px; display: grid; gap: 16px; grid-template-columns: 340px 1fr; }
  @media (max-width: 820px) { main { grid-template-columns: 1fr; } }
  .card { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius); padding: 16px; }
  .card h2 { font-size: 15px; margin: 0 0 12px; }
  label { display: block; font-size: 12px; color: var(--muted); margin: 10px 0 4px; }
  input, select, textarea, button { font: inherit; color: inherit; }
  input, select, textarea { width: 100%; padding: 8px 10px; border: 1px solid var(--line); border-radius: 8px; background: var(--bg); }
  textarea { resize: vertical; min-height: 56px; }
  button { padding: 8px 14px; border-radius: 8px; border: 1px solid var(--line); background: var(--card); cursor: pointer; }
  button.primary { background: var(--accent); border-color: var(--accent); color: #fff; }
  button.danger { color: var(--danger); }
  button.link { border: none; background: none; padding: 4px 6px; color: var(--accent); }
  .row { display: flex; gap: 8px; align-items: end; }
  .row > * { flex: 1; }
  .actions { display: flex; gap: 8px; margin-top: 14px; }
  .stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
  .stat { background: var(--card); border: 1px solid var(--line); border-radius: var(--radius); padding: 14px 16px; }
  .stat .k { font-size: 12px; color: var(--muted); }
  .stat .v { font-size: 22px; font-weight: 600; margin-top: 2px; font-variant-numeric: tabular-nums; }
  .filters { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
  @media (max-width: 600px) {
    .filters { grid-template-columns: 1fr 1fr; }
    .stat { padding: 10px 12px; } .stat .v { font-size: 16px; }
    th:nth-child(3), td.note { display: none; }
    td.cat-note { display: block; }
    .bar { grid-template-columns: 90px 1fr 90px; }
    th, td { padding: 8px 3px; } button.link { padding: 4px 3px; } main, header { padding-left: 14px; padding-right: 14px; }
  }
  .filters label { margin-top: 0; }
  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  th, td { text-align: left; padding: 8px 6px; border-bottom: 1px solid var(--line); vertical-align: top; }
  th { font-size: 12px; color: var(--muted); font-weight: 500; }
  td.amt, th.amt { text-align: right; font-variant-numeric: tabular-nums; white-space: nowrap; }
  td.note { color: var(--muted); max-width: 240px; }
  .pill { display: inline-block; padding: 1px 8px; border-radius: 999px; font-size: 12px; color: #fff; }
  .empty { color: var(--muted); text-align: center; padding: 24px 0; }
  .bars { display: grid; gap: 8px; }
  .bar { display: grid; grid-template-columns: 110px 1fr 110px; align-items: center; gap: 10px; font-size: 13px; }
  .bar .track { height: 14px; background: var(--bg); border-radius: 4px; overflow: hidden; }
  .bar .fill { height: 100%; border-radius: 4px; }
  .bar .val { text-align: right; font-variant-numeric: tabular-nums; }
  .right { display: grid; gap: 16px; align-content: start; }
  .settings { display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
  .settings select { width: auto; }
  .muted { color: var(--muted); font-size: 12px; }
  .error { color: var(--danger); font-size: 13px; margin-top: 10px; }
</style>
</head>
<body>
<header>
  <h1>Expenses</h1>
  <div class="settings">
    <label for="currency" style="margin:0">Currency</label>
    <select id="currency"></select>
    <button id="exportBtn" title="Download all expenses as CSV">Export CSV</button>
    <form method="POST" action="{{ route('logout') }}" style="margin:0">
      @csrf
      <button type="submit" title="Signed in as {{ auth()->user()->email }}">Sign out</button>
    </form>
  </div>
</header>

<main>
  <section class="card" aria-labelledby="formTitle">
    <h2 id="formTitle">Add expense</h2>
    <form id="form" autocomplete="off">
      <input type="hidden" id="editId">
      <label for="amount">Amount</label>
      <input id="amount" type="number" step="0.01" min="0.01" required placeholder="0.00">
      <div class="row">
        <div>
          <label for="category">Category</label>
          <select id="category" required></select>
        </div>
        <div>
          <label for="date">Date</label>
          <input id="date" type="date" required>
        </div>
      </div>
      <label for="note">Note</label>
      <textarea id="note" placeholder="Optional"></textarea>
      <div class="actions">
        <button type="submit" class="primary" id="submitBtn">Add</button>
        <button type="button" id="cancelBtn" hidden>Cancel</button>
      </div>
      <div class="error" id="formError" role="alert" hidden></div>
    </form>
    <p class="muted" style="margin-top:16px">Saved to the app database.</p>
  </section>

  <div class="right">
    <div class="stats">
      <div class="stat"><div class="k" id="monthLabel">This month</div><div class="v" id="statMonth">–</div></div>
      <div class="stat"><div class="k">Filtered total</div><div class="v" id="statFiltered">–</div></div>
      <div class="stat"><div class="k">Entries shown</div><div class="v" id="statCount">–</div></div>
    </div>

    <section class="card">
      <h2>By category <span class="muted" id="chartScope"></span></h2>
      <div class="bars" id="chart"></div>
    </section>

    <section class="card">
      <h2>Expenses</h2>
      <div class="filters">
        <div><label for="fMonth">Month</label><select id="fMonth"></select></div>
        <div><label for="fCategory">Category</label><select id="fCategory"></select></div>
        <div><label for="fSearch">Search notes</label><input id="fSearch" type="search" placeholder="e.g. jeepney"></div>
        <div><label for="fSort">Sort</label>
          <select id="fSort">
            <option value="date-desc">Newest first</option>
            <option value="date-asc">Oldest first</option>
            <option value="amt-desc">Highest amount</option>
            <option value="amt-asc">Lowest amount</option>
          </select>
        </div>
      </div>
      <table>
        <thead><tr><th>Date</th><th>Category</th><th>Note</th><th class="amt">Amount</th><th></th></tr></thead>
        <tbody id="list"></tbody>
      </table>
    </section>
  </div>
</main>

<script>
(() => {
  const CATEGORIES = [
    ['Food', '#f59e0b'], ['Transport', '#3b82f6'], ['Bills', '#8b5cf6'], ['Groceries', '#10b981'],
    ['Shopping', '#ec4899'], ['Health', '#ef4444'], ['Entertainment', '#06b6d4'], ['Farm Input', '#a16207'], ['Other', '#6b7280'],
  ];
  const COLOR = Object.fromEntries(CATEGORIES);
  const CURRENCIES = @json(\App\Http\Controllers\SettingController::CURRENCIES);

  const $ = id => document.getElementById(id);
  let state = { currency: 'PHP', items: [] };

  async function api(method, url, body) {
    const res = await fetch(url, {
      method,
      headers: {
        'Accept': 'application/json',
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
      },
      body: body === undefined ? undefined : JSON.stringify(body),
    });
    if (res.status === 401 || res.status === 419) { location.href = '/login'; throw new Error('Signed out'); }
    if (res.status === 204) return null;
    const json = await res.json().catch(() => ({}));
    if (!res.ok) {
      const errs = json.errors ? Object.values(json.errors).flat().join(' ') : (json.message || 'Request failed');
      throw new Error(errs);
    }
    return json;
  }
  const showError = msg => { $('formError').textContent = msg || ''; $('formError').hidden = !msg; };

  const fmt = n => {
    try { return new Intl.NumberFormat(undefined, { style: 'currency', currency: state.currency }).format(n); }
    catch { return state.currency + ' ' + n.toFixed(2); }
  };
  const today = () => { const d = new Date(); d.setMinutes(d.getMinutes() - d.getTimezoneOffset()); return d.toISOString().slice(0, 10); };
  const monthKey = iso => iso.slice(0, 7);
  const monthName = key => {
    const [y, m] = key.split('-').map(Number);
    return new Date(y, m - 1, 1).toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
  };
  const esc = s => s.replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

  // Static selects
  $('currency').innerHTML = CURRENCIES.map(c => `<option>${c}</option>`).join('');
  $('category').innerHTML = CATEGORIES.map(([c]) => `<option>${c}</option>`).join('');
  $('fCategory').innerHTML = '<option value="">All</option>' + CATEGORIES.map(([c]) => `<option>${c}</option>`).join('');
  $('date').value = today();

  function refreshMonthFilter() {
    const sel = $('fMonth'), prev = sel.value;
    const months = [...new Set([monthKey(today()), ...state.items.map(i => monthKey(i.date))])].sort().reverse();
    sel.innerHTML = '<option value="">All months</option>' + months.map(m => `<option value="${m}">${monthName(m)}</option>`).join('');
    sel.value = months.includes(prev) || prev === '' ? prev : monthKey(today());
  }

  function filtered() {
    const m = $('fMonth').value, c = $('fCategory').value, q = $('fSearch').value.trim().toLowerCase();
    let rows = state.items.filter(i =>
      (!m || monthKey(i.date) === m) && (!c || i.category === c) && (!q || i.note.toLowerCase().includes(q)));
    const [key, dir] = $('fSort').value.split('-');
    const sign = dir === 'asc' ? 1 : -1;
    rows.sort((a, b) => key === 'amt' ? sign * (a.amount - b.amount) : sign * (a.date.localeCompare(b.date) || a.id - b.id));
    return rows;
  }

  function render() {
    const rows = filtered();
    const sum = arr => arr.reduce((s, i) => s + i.amount, 0);

    // Stats
    const thisMonth = monthKey(today());
    $('monthLabel').textContent = monthName(thisMonth);
    $('statMonth').textContent = fmt(sum(state.items.filter(i => monthKey(i.date) === thisMonth)));
    $('statFiltered').textContent = fmt(sum(rows));
    $('statCount').textContent = rows.length;

    // Table
    $('list').innerHTML = rows.length ? rows.map(i => `
      <tr>
        <td style="white-space:nowrap">${new Date(i.date + 'T00:00').toLocaleDateString(undefined, { month: 'short', day: 'numeric', ...(i.date.slice(0, 4) !== today().slice(0, 4) && { year: 'numeric' }) })}</td>
        <td><span class="pill" style="background:${COLOR[i.category] || '#6b7280'}">${esc(i.category)}</span></td>
        <td class="note">${esc(i.note)}</td>
        <td class="amt">${fmt(i.amount)}</td>
        <td style="white-space:nowrap">
          <button class="link" data-edit="${i.id}">Edit</button>
          <button class="link danger" data-del="${i.id}">Delete</button>
        </td>
      </tr>`).join('') : `<tr><td colspan="5" class="empty">No expenses here yet.</td></tr>`;

    // Chart (follows the month + search filters, all categories)
    const m = $('fMonth').value;
    $('chartScope').textContent = m ? '· ' + monthName(m) : '· all time';
    const q = $('fSearch').value.trim().toLowerCase();
    const scope = state.items.filter(i => (!m || monthKey(i.date) === m) && (!q || i.note.toLowerCase().includes(q)));
    const totals = CATEGORIES.map(([c, col]) => ({ c, col, v: sum(scope.filter(i => i.category === c)) }))
      .filter(t => t.v > 0).sort((a, b) => b.v - a.v);
    const max = Math.max(...totals.map(t => t.v), 1), all = sum(scope) || 1;
    $('chart').innerHTML = totals.length ? totals.map(t => `
      <div class="bar" title="${Math.round(t.v / all * 100)}% of total">
        <span>${t.c}</span>
        <div class="track"><div class="fill" style="width:${t.v / max * 100}%;background:${t.col}"></div></div>
        <span class="val">${fmt(t.v)}</span>
      </div>`).join('') : '<div class="empty">Nothing to chart for this period.</div>';
  }

  function resetForm() {
    $('form').reset();
    showError('');
    $('editId').value = '';
    $('date').value = today();
    $('formTitle').textContent = 'Add expense';
    $('submitBtn').textContent = 'Add';
    $('cancelBtn').hidden = true;
  }

  $('form').addEventListener('submit', async e => {
    e.preventDefault();
    const amount = Math.round(parseFloat($('amount').value) * 100) / 100;
    if (!(amount > 0)) return $('amount').focus();
    const data = { amount, category: $('category').value, date: $('date').value, note: $('note').value.trim() };
    const id = Number($('editId').value);
    $('submitBtn').disabled = true;
    try {
      if (id) {
        const saved = await api('PUT', `/api/expenses/${id}`, data);
        Object.assign(state.items.find(i => i.id === id), saved);
      } else {
        state.items.push(await api('POST', '/api/expenses', data));
      }
      resetForm(); refreshMonthFilter(); render();
      $('amount').focus();
    } catch (err) {
      showError(err.message);
    } finally {
      $('submitBtn').disabled = false;
    }
  });
  $('cancelBtn').addEventListener('click', resetForm);

  $('list').addEventListener('click', async e => {
    const editId = Number(e.target.dataset.edit), delId = Number(e.target.dataset.del);
    if (editId) {
      const i = state.items.find(x => x.id === editId);
      $('editId').value = i.id; $('amount').value = i.amount; $('category').value = i.category;
      $('date').value = i.date; $('note').value = i.note;
      $('formTitle').textContent = 'Edit expense'; $('submitBtn').textContent = 'Save';
      $('cancelBtn').hidden = false; showError(''); $('amount').focus();
    }
    if (delId) {
      const btn = e.target;
      if (btn.dataset.confirm) {
        try {
          await api('DELETE', `/api/expenses/${delId}`);
        } catch (err) {
          btn.textContent = 'Failed'; return;
        }
        state.items = state.items.filter(x => x.id !== delId);
        if (Number($('editId').value) === delId) resetForm();
        refreshMonthFilter(); render();
      } else {
        btn.dataset.confirm = '1'; btn.textContent = 'Confirm?';
        setTimeout(() => { if (btn.isConnected) { delete btn.dataset.confirm; btn.textContent = 'Delete'; } }, 3000);
      }
    }
  });

  ['fMonth', 'fCategory', 'fSort'].forEach(id => $(id).addEventListener('change', render));
  $('fSearch').addEventListener('input', render);
  $('currency').addEventListener('change', async e => {
    const prev = state.currency;
    state.currency = e.target.value; render();
    try { await api('PUT', '/api/settings', { currency: state.currency }); }
    catch { state.currency = prev; e.target.value = prev; render(); }
  });

  $('exportBtn').addEventListener('click', () => {
    const q = v => `"${String(v).replace(/"/g, '""')}"`;
    const csv = ['date,category,amount,currency,note',
      ...[...state.items].sort((a, b) => a.date.localeCompare(b.date))
        .map(i => [i.date, i.category, i.amount.toFixed(2), state.currency, q(i.note)].join(','))].join('\n');
    const a = document.createElement('a');
    a.href = URL.createObjectURL(new Blob([csv], { type: 'text/csv' }));
    a.download = `expenses-${today()}.csv`; a.click();
    URL.revokeObjectURL(a.href);
  });

  (async () => {
    try {
      const [settings, items] = await Promise.all([api('GET', '/api/settings'), api('GET', '/api/expenses')]);
      state = { currency: settings.currency, items };
    } catch (err) {
      showError('Could not load expenses from the server: ' + err.message);
    }
    $('currency').value = state.currency;
    refreshMonthFilter();
    $('fMonth').value = monthKey(today());
    render();
  })();
})();
</script>
</body>
</html>
