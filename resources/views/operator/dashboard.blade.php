<!doctype html>
<html lang="ru">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Operator Dashboard</title>
  <style>
    :root {
      --bg: #0b1220;
      --card: #111a2e;
      --border: #1f2a44;
      --text: #e8eefc;
      --muted: #8fa3c8;
      --green: #21c36b;
      --orange: #f59e0b;
      --gray: #64748b;
      --red: #ef4444;
      --blue: #60a5fa;
    }

    body {
      margin: 0;
      background: linear-gradient(180deg, #070b14, var(--bg));
      color: var(--text);
      font-family: Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, Arial;
    }

    .wrap {
      max-width: 1200px;
      margin: 0 auto;
      padding: 18px;
    }

    .topbar {
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      gap: 14px;
      flex-wrap: wrap;
    }

    .operator-card {
      width: 340px;
      max-width: 100%;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 14px;
    }

    .title {
      font-size: 16px;
      font-weight: 700;
      margin: 0 0 10px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      gap: 10px;
      padding: 8px 0;
      border-bottom: 1px dashed rgba(255, 255, 255, 0.08);
    }

    .row:last-child {
      border-bottom: none;
    }

    .label {
      color: var(--muted);
      font-size: 12px;
    }

    .value {
      font-weight: 700;
    }

    .hint {
      color: var(--muted);
      font-size: 12px;
      margin-top: 8px;
      line-height: 1.3;
    }

    .statusline {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
    }

    .dot {
      width: 10px;
      height: 10px;
      border-radius: 999px;
      background: var(--green);
      box-shadow: 0 0 0 4px rgba(33, 195, 107, 0.15);
    }

    .online {
      font-size: 13px;
      color: var(--green);
      font-weight: 700;
    }

    .btn {
      background: rgba(96, 165, 250, 0.15);
      border: 1px solid rgba(96, 165, 250, 0.25);
      color: var(--blue);
      border-radius: 10px;
      padding: 10px 12px;
      cursor: pointer;
      font-weight: 800;
    }

    .btn.secondary {
      background: rgba(255, 255, 255, 0.04);
      border-color: rgba(255, 255, 255, 0.12);
      color: var(--text);
    }

    .btn.danger {
      background: rgba(239, 68, 68, 0.12);
      border-color: rgba(239, 68, 68, 0.22);
      color: var(--red);
    }

    .btn:disabled {
      opacity: .5;
      cursor: not-allowed;
    }

    .orders-card {
      margin-top: 14px;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 14px;
    }

    .toolbar {
      display: flex;
      gap: 10px;
      flex-wrap: wrap;
      align-items: center;
      justify-content: space-between;
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px solid rgba(255, 255, 255, 0.06);
    }

    .toolbar-left,
    .toolbar-right {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }

    input[type="text"],
    input[type="number"],
    textarea,
    select {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: var(--text);
      border-radius: 10px;
      padding: 10px 10px;
      outline: none;
      min-width: 240px;
    }

    textarea {
      min-width: 100%;
      resize: vertical;
    }

    select {
      min-width: 120px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 14px;
      overflow: hidden;
      border-radius: 14px;
    }

    thead th {
      text-align: left;
      font-size: 12px;
      color: var(--muted);
      font-weight: 600;
      padding: 10px 12px;
      background: rgba(255, 255, 255, 0.03);
      border-bottom: 1px solid var(--border);
    }

    tbody td {
      padding: 10px 12px;
      border-bottom: 1px solid rgba(255, 255, 255, 0.06);
      font-size: 13px;
      vertical-align: top;
    }

    tbody tr:hover {
      background: rgba(255, 255, 255, 0.03);
    }

    .badge {
      display: inline-flex;
      align-items: center;
      padding: 4px 8px;
      border-radius: 999px;
      font-size: 12px;
      font-weight: 800;
      border: 1px solid rgba(255, 255, 255, 0.10);
      white-space: nowrap;
    }

    .paid {
      background: rgba(33, 195, 107, 0.15);
      color: var(--green);
    }

    .pending {
      background: rgba(245, 158, 11, 0.15);
      color: var(--orange);
    }

    .expired {
      background: rgba(100, 116, 139, 0.15);
      color: #b7c3da;
    }

    .failed {
      background: rgba(239, 68, 68, 0.15);
      color: var(--red);
    }

    .approved {
      background: rgba(33, 195, 107, 0.15);
      color: var(--green);
    }

    .rejected {
      background: rgba(239, 68, 68, 0.15);
      color: var(--red);
    }

    .mono {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono";
      font-size: 12px;
    }

    .pagination {
      display: flex;
      gap: 8px;
      align-items: center;
      justify-content: flex-end;
    }

    .page {
      width: 34px;
      height: 34px;
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      border: 1px solid rgba(255, 255, 255, 0.12);
      background: rgba(255, 255, 255, 0.03);
      color: var(--text);
      cursor: pointer;
      user-select: none;
    }

    .page.active {
      border-color: rgba(96, 165, 250, 0.35);
      background: rgba(96, 165, 250, 0.15);
      color: var(--blue);
      font-weight: 900;
    }

    .page.disabled {
      opacity: .5;
      cursor: not-allowed;
    }

    .backdrop {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.55);
      display: none;
      align-items: center;
      justify-content: center;
      padding: 16px;
    }

    .modal {
      width: min(720px, 100%);
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 14px;
    }

    .modal-head {
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
      margin-bottom: 10px;
    }

    .modal-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    .card {
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.10);
      border-radius: 12px;
      padding: 12px;
    }

    .kv {
      font-size: 12px;
      color: var(--muted);
    }

    .pre {
      white-space: pre-wrap;
      word-break: break-word;
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono";
      font-size: 12px;
    }

    .right {
      display: flex;
      gap: 10px;
      align-items: center;
      flex-wrap: wrap;
    }

    @media (max-width: 900px) {
      .modal-grid {
        grid-template-columns: 1fr;
      }
    }

    @media (max-width: 720px) {

      input[type="text"],
      input[type="number"] {
        min-width: 100%;
      }

      .pagination {
        justify-content: flex-start;
        width: 100%;
      }

      .operator-card {
        width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="wrap">

    <div class="topbar">
      <div class="operator-card">
        <div class="statusline">
          <div class="dot"></div>
          <div class="online">Оператор: ONLINE</div>
        </div>

        <div class="title">Оператор</div>

        <div class="row">
          <div class="label">Баланс оператора (USDT)</div>
          <div class="value" id="usdtBalance">—</div>
        </div>

        <div class="row">
          <div class="label">Курс USDT/RUB</div>
          <div class="value" id="usdtRate">—</div>
        </div>

        <div class="row">
          <div class="label">Комиссия оператора (итого, USDT)</div>
          <div class="value" id="totalFee">0</div>
        </div>

        <div class="row">
          <div class="label">Процент</div>
          <div class="value">14%</div>
        </div>

        <div class="hint" id="summaryHint">Источник: —<br>Обновлено: —</div>

        <div style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
          <button class="btn" id="openTopupBtn">Пополнить счёт</button>
          <a class="btn secondary" href="/admin/dashboard" target="_blank" rel="noreferrer">Админка</a>
          <form method="POST" action="/logout" style="margin:0;">
            @csrf
            <button class="btn secondary" type="submit">Выйти</button>
          </form>
        </div>
      </div>
    </div>

    <div class="orders-card">
      <div class="title">Ордера</div>
      <div class="hint">Фильтры: pending/paid/failed/expired. Автообновление — 5 сек.</div>

      <div class="toolbar">
        <div class="toolbar-left">
          <input id="searchInput" type="text" placeholder="Поиск: order_id / user_id / txid ..." />
          <select id="limitSelect">
            <option value="10">10 / стр</option>
            <option value="25" selected>25 / стр</option>
            <option value="50">50 / стр</option>
            <option value="100">100 / стр</option>
          </select>
          <select id="statusSelect">
            <option value="all" selected>Все</option>
            <option value="pending">В процессе</option>
            <option value="paid">Завершённые</option>
            <option value="rejected">Отклонённые</option>
          </select>
          <button class="btn" id="applyBtn">Применить</button>
        </div>

        <div class="toolbar-right">
          <div class="pagination" id="pagination"></div>
        </div>
      </div>

      <table>
        <thead>
          <tr>
            <th>Order</th>
            <th>Amount</th>
            <th>User ID</th>
            <th>Method</th>
            <th>Status</th>
            <th>Списано (USDT)</th>
            <th>Осталось</th>
            <th>Fee (USDT)</th>
          </tr>
        </thead>
        <tbody id="ordersTbody"></tbody>
      </table>

      <div class="hint" style="margin-top:10px;" id="ordersHint">—</div>
    </div>

    <div class="orders-card">
      <div class="title">Мои пополнения</div>
      <div class="hint">Создай заявку, оплати по реквизитам, затем админ подтвердит.</div>

      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Amount (USDT)</th>
            <th>Status</th>
            <th>Комментарий</th>
            <th>Admin</th>
            <th>Создано</th>
          </tr>
        </thead>
        <tbody id="topupsTbody"></tbody>
      </table>
      <div class="hint" style="margin-top:10px;" id="topupsHint">—</div>
    </div>

  </div>

  <div class="backdrop" id="topupBackdrop">
    <div class="modal">
      <div class="modal-head">
        <div>
          <div class="title" style="margin:0;">Пополнить счёт оператора</div>
          <div class="hint" style="margin:6px 0 0;">Создай заявку → оплати → админ подтвердит.</div>
        </div>
        <div class="right">
          <button class="btn secondary" id="reloadRequisitesBtn">Обновить</button>
          <button class="btn danger" id="closeTopupBtn">Закрыть</button>
        </div>
      </div>

      <div class="modal-grid">
        <div class="card">
          <div class="title" style="margin:0 0 8px;">Создать заявку</div>
          <div class="kv">Сумма (USDT)</div>
          <input id="topupAmount" type="number" step="0.000001" min="0.01" placeholder="например 100" />
          <div style="height:10px;"></div>
          <div class="kv">Комментарий</div>
          <textarea id="topupComment" rows="3" placeholder="например: пополнение от 23.01"></textarea>
          <div style="height:12px;"></div>
          <button class="btn" id="createTopupBtn">Создать заявку</button>
          <div class="hint" id="topupCreateHint" style="margin-top:10px;">—</div>
        </div>

        <div class="card">
          <div class="title" style="margin:0 0 8px;">Реквизиты для оплаты</div>
          <div class="hint" style="margin-top:0;">Оператор видит только активные реквизиты.</div>
          <div id="requisitesBox" style="margin-top:10px; display:flex; flex-direction:column; gap:10px;"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function csrfToken() {
      const el = document.querySelector('meta[name="csrf-token"]');
      return el ? el.getAttribute('content') : '';
    }

    let state = {
      page: 1,
      perPage: 25,
      q: '',
      status: 'all',
      orders: [],
      pagination: {
        total: 0,
        per_page: 25,
        current_page: 1,
        last_page: 1
      },
      requisites: [],
      topups: []
    };

    function badgeClass(status) {
      if (status === 'paid') return 'paid';
      if (status === 'expired') return 'expired';
      if (status === 'failed') return 'failed';
      if (status === 'approved') return 'approved';
      if (status === 'rejected') return 'rejected';
      return 'pending';
    }

    function renderPagination(p) {
      const root = document.getElementById('pagination');
      root.innerHTML = '';

      const add = (label, page, opts = {}) => {
        const el = document.createElement('div');
        el.className = 'page' + (opts.active ? ' active' : '') + (opts.disabled ? ' disabled' : '');
        el.textContent = label;
        if (!opts.disabled) el.onclick = () => {
          state.page = page;
          loadOrdersFromServer();
        };
        root.appendChild(el);
      };

      add('«', Math.max(1, p.current_page - 1), {
        disabled: p.current_page === 1
      });

      const maxButtons = 7;
      let start = Math.max(1, p.current_page - Math.floor(maxButtons / 2));
      let end = Math.min(p.last_page, start + maxButtons - 1);
      start = Math.max(1, end - maxButtons + 1);

      for (let i = start; i <= end; i++) add(String(i), i, {
        active: i === p.current_page
      });

      add('»', Math.min(p.last_page, p.current_page + 1), {
        disabled: p.current_page === p.last_page
      });
    }

    function renderOrders() {
      const tbody = document.getElementById('ordersTbody');
      tbody.innerHTML = '';
      const now = Date.now();

      for (const o of state.orders) {
        const tr = document.createElement('tr');

        const expiresAt = o.expires_at ? Date.parse(o.expires_at) : null;
        let left = '—';
        if (o.status === 'pending' && expiresAt) {
          const diff = Math.max(0, Math.floor((expiresAt - now) / 1000));
          const mm = String(Math.floor(diff / 60)).padStart(2, '0');
          const ss = String(diff % 60).padStart(2, '0');
          left = `${mm}:${ss}`;
        }

        const amountText = `${o.amount ?? '—'} ${o.currency ?? ''}`.trim();

        tr.innerHTML = `
        <td class="mono">${o.order_id ?? '—'}</td>
        <td>${amountText}</td>
        <td>${o.user_id ?? '—'}</td>
        <td>${o.method ?? '—'}</td>
        <td><span class="badge ${badgeClass(o.status)}">${o.status ?? '—'}</span></td>
        <td>${o.operator_debit_usdt ?? '—'}</td>
        <td class="mono">${left}</td>
        <td>${o.operator_fee_usdt ?? '—'}</td>
      `;
        tbody.appendChild(tr);
      }

      renderPagination(state.pagination);
      document.getElementById('ordersHint').textContent =
        `Найдено: ${state.pagination.total}. Страница: ${state.pagination.current_page}/${state.pagination.last_page}. Показано: ${state.orders.length}.`;
    }

    function renderTopups() {
      const tbody = document.getElementById('topupsTbody');
      tbody.innerHTML = '';

      for (const t of state.topups) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td class="mono">${t.id ?? '—'}</td>
        <td>${t.amount_usdt ?? '—'}</td>
        <td><span class="badge ${badgeClass(t.status)}">${t.status ?? '—'}</span></td>
        <td>${t.operator_comment ?? '—'}</td>
        <td>${t.admin_comment ?? '—'}</td>
        <td class="mono">${t.created_at ?? '—'}</td>
      `;
        tbody.appendChild(tr);
      }

      document.getElementById('topupsHint').textContent = `Последних заявок: ${state.topups.length}`;
    }

    function renderRequisites() {
      const box = document.getElementById('requisitesBox');
      box.innerHTML = '';

      if (!state.requisites.length) {
        box.innerHTML = `<div class="hint">Реквизиты не заданы админом.</div>`;
        return;
      }

      for (const r of state.requisites) {
        const el = document.createElement('div');
        el.className = 'card';
        el.innerHTML = `
        <div style="display:flex;justify-content:space-between;gap:10px;align-items:flex-start;">
          <div>
            <div style="font-weight:900;">${r.title ?? '—'}</div>
            <div class="hint" style="margin-top:4px;">Сеть: ${r.network ?? '—'}</div>
          </div>
          <button class="btn secondary">Копировать</button>
        </div>
        <div style="height:10px;"></div>
        <div class="pre">${r.details ?? '—'}</div>
      `;
        el.querySelector('button').onclick = async () => {
          const text = r.details || '';
          try {
            await navigator.clipboard.writeText(text);
          } catch (_) {}
        };
        box.appendChild(el);
      }
    }

    async function loadOrdersFromServer() {
      const params = new URLSearchParams();
      params.set('page', String(state.page));
      params.set('per_page', String(state.perPage));
      params.set('status', String(state.status));
      if (state.q.trim() !== '') params.set('q', state.q.trim());

      const res = await fetch('/operator/orders?' + params.toString(), {
        headers: {
          'Accept': 'application/json'
        }
      });
      const data = await res.json();

      document.getElementById('totalFee').textContent = data.total_operator_fee_usdt ?? '0';

      state.orders = data.orders || [];
      state.pagination = data.pagination || {
        total: 0,
        per_page: state.perPage,
        current_page: state.page,
        last_page: 1
      };
      state.page = state.pagination.current_page;

      renderOrders();
    }

    async function loadSummary() {
      const res = await fetch('/operator/summary', {
        headers: {
          'Accept': 'application/json'
        }
      });
      const data = await res.json();

      document.getElementById('usdtBalance').textContent = data.operator_usdt_balance ?? 'нет данных';
      document.getElementById('usdtRate').textContent = data.usdt_rub_rate ?? 'нет данных';
      document.getElementById('summaryHint').innerHTML = `Источник: ${data.source ?? '—'}<br>Обновлено: ${data.updated_at ?? '—'}`;
    }

    async function loadTopupsAndRequisites() {
      const res = await fetch('/operator/topups', {
        headers: {
          'Accept': 'application/json'
        }
      });
      const data = await res.json();

      state.requisites = data.requisites || [];
      state.topups = data.topups || [];

      renderRequisites();
      renderTopups();
    }

    async function createTopup() {
      const hint = document.getElementById('topupCreateHint');
      hint.textContent = 'Отправляем...';

      const amount = document.getElementById('topupAmount').value;
      const comment = document.getElementById('topupComment').value;

      const res = await fetch('/operator/topups', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        },
        body: JSON.stringify({
          amount_usdt: amount,
          operator_comment: comment
        })
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok) {
        hint.textContent = data?.message ? `Ошибка: ${data.message}` : 'Ошибка создания заявки';
        return;
      }

      hint.textContent = `Заявка создана: #${data.topup?.id ?? '—'}`;
      document.getElementById('topupAmount').value = '';
      document.getElementById('topupComment').value = '';

      await loadTopupsAndRequisites();
      await loadSummary();
    }

    // modal
    const backdrop = document.getElementById('topupBackdrop');
    document.getElementById('openTopupBtn').onclick = async () => {
      backdrop.style.display = 'flex';
      await loadTopupsAndRequisites();
    };
    document.getElementById('closeTopupBtn').onclick = () => {
      backdrop.style.display = 'none';
    };
    backdrop.addEventListener('click', (e) => {
      if (e.target === backdrop) backdrop.style.display = 'none';
    });
    document.getElementById('reloadRequisitesBtn').onclick = loadTopupsAndRequisites;
    document.getElementById('createTopupBtn').onclick = createTopup;

    // orders UI
    document.getElementById('applyBtn').onclick = () => {
      state.q = document.getElementById('searchInput').value || '';
      state.perPage = parseInt(document.getElementById('limitSelect').value, 10) || 25;
      state.status = document.getElementById('statusSelect').value || 'all';
      state.page = 1;
      loadOrdersFromServer();
    };
    document.getElementById('searchInput').addEventListener('keydown', (e) => {
      if (e.key === 'Enter') document.getElementById('applyBtn').click();
    });
    document.getElementById('statusSelect').addEventListener('change', () => document.getElementById('applyBtn').click());
    document.getElementById('limitSelect').addEventListener('change', () => document.getElementById('applyBtn').click());

    loadSummary();
    loadOrdersFromServer();
    loadTopupsAndRequisites();

    setInterval(loadOrdersFromServer, 5000);
    setInterval(loadSummary, 15000);
    setInterval(loadTopupsAndRequisites, 8000);
    setInterval(() => {
      if (state.orders.length) renderOrders();
    }, 1000);
  </script>
</body>

</html>