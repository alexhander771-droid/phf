<!doctype html>
<html lang="ru">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Admin Dashboard</title>
  <style>
    :root {
      --bg: #0b1220;
      --card: #111a2e;
      --border: #1f2a44;
      --text: #e8eefc;
      --muted: #8fa3c8;
      --green: #21c36b;
      --orange: #f59e0b;
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

    .card {
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 14px;
      margin-top: 14px;
    }

    .title {
      font-size: 16px;
      font-weight: 900;
      margin: 0 0 10px;
    }

    .hint {
      color: var(--muted);
      font-size: 12px;
      margin-top: 8px;
      line-height: 1.3;
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

    .left,
    .right {
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
      min-width: 220px;
    }

    textarea {
      min-width: 100%;
      resize: vertical;
    }

    .btn {
      background: rgba(96, 165, 250, 0.15);
      border: 1px solid rgba(96, 165, 250, 0.25);
      color: var(--blue);
      border-radius: 10px;
      padding: 10px 12px;
      cursor: pointer;
      font-weight: 900;
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

    .btn.ok {
      background: rgba(33, 195, 107, 0.14);
      border-color: rgba(33, 195, 107, 0.24);
      color: var(--green);
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 12px;
      overflow: hidden;
      border-radius: 14px;
    }

    thead th {
      text-align: left;
      font-size: 12px;
      color: var(--muted);
      font-weight: 700;
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
      font-weight: 900;
      border: 1px solid rgba(255, 255, 255, 0.10);
    }

    .pending {
      background: rgba(245, 158, 11, 0.15);
      color: var(--orange);
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

    .grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 14px;
    }

    @media (max-width: 900px) {
      .grid {
        grid-template-columns: 1fr;
      }

      input[type="text"],
      input[type="number"] {
        min-width: 100%;
      }
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center; justify-content:space-between;">
      <div class="title" style="margin:0;">Админка</div>
      <div class="right">
        <a class="btn secondary" href="/operator" target="_blank" rel="noreferrer">Оператор</a>
        <form method="POST" action="/logout" style="margin:0;">
          @csrf
          <button class="btn secondary" type="submit">Выйти</button>
        </form>
      </div>
    </div>

    <div class="grid">
      <div class="card">
        <div class="title">Реквизиты пополнения</div>
        <div class="hint">Админ добавляет реквизиты. Оператор видит только активные.</div>

        <div style="margin-top:10px;">
          <div class="hint" style="margin:0 0 6px;">Новый реквизит</div>
          <input id="reqTitle" type="text" placeholder="Название (например USDT TRC20)" />
          <div style="height:8px;"></div>
          <input id="reqNetwork" type="text" placeholder="Сеть (TRC20/ERC20/Банк)" />
          <div style="height:8px;"></div>
          <textarea id="reqDetails" rows="4" placeholder="Детали (адрес кошелька, банк, комментарий)"></textarea>
          <div style="height:10px;"></div>
          <button class="btn" id="createReqBtn">Добавить</button>
          <div class="hint" id="reqHint">—</div>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Title</th>
              <th>Active</th>
              <th></th>
            </tr>
          </thead>
          <tbody id="reqTbody"></tbody>
        </table>
      </div>

      <div class="card">
        <div class="title">Пополнения операторов</div>
        <div class="hint">Подтверждение увеличивает баланс оператора.</div>

        <div class="toolbar">
          <div class="left">
            <select id="topupStatus">
              <option value="all" selected>Все</option>
              <option value="pending">Pending</option>
              <option value="approved">Approved</option>
              <option value="rejected">Rejected</option>
            </select>
            <button class="btn" id="reloadTopupsBtn">Обновить</button>
          </div>
          <div class="right">
            <span class="hint" id="topupsMeta">—</span>
          </div>
        </div>

        <table>
          <thead>
            <tr>
              <th>ID</th>
              <th>Operator</th>
              <th>Amount</th>
              <th>Status</th>
              <th>Operator comment</th>
              <th>Admin comment</th>
              <th>Created</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="topupsTbody"></tbody>
        </table>

        <div class="hint" id="adminHint">—</div>
      </div>
    </div>
  </div>

  <script>
    function csrfToken() {
      const el = document.querySelector('meta[name="csrf-token"]');
      return el ? el.getAttribute('content') : '';
    }

    let adminState = {
      requisites: [],
      topups: [],
      topupsPaginator: null
    };

    function badgeClass(status) {
      if (status === 'approved') return 'approved';
      if (status === 'rejected') return 'rejected';
      return 'pending';
    }

    async function loadRequisites() {
      const res = await fetch('/admin/requisites', {
        headers: {
          'Accept': 'application/json'
        }
      });
      const data = await res.json();

      adminState.requisites = data.requisites || data || [];
      renderRequisites();
    }

    function renderRequisites() {
      const tbody = document.getElementById('reqTbody');
      tbody.innerHTML = '';

      for (const r of adminState.requisites) {
        const tr = document.createElement('tr');
        tr.innerHTML = `
        <td class="mono">${r.id}</td>
        <td>
          <div style="font-weight:900;">${r.title ?? '—'}</div>
          <div class="hint" style="margin:4px 0 0;">${r.network ?? ''}</div>
        </td>
        <td>${r.is_active ? 'yes' : 'no'}</td>
        <td>
          <button class="btn secondary" data-toggle="${r.id}">${r.is_active ? 'Выключить' : 'Включить'}</button>
        </td>
      `;
        tr.querySelector('button[data-toggle]').onclick = () => toggleReq(r.id);
        tbody.appendChild(tr);
      }
    }

    async function createReq() {
      const hint = document.getElementById('reqHint');
      hint.textContent = 'Сохраняем...';

      const payload = {
        title: document.getElementById('reqTitle').value,
        network: document.getElementById('reqNetwork').value,
        details: document.getElementById('reqDetails').value
      };

      const res = await fetch('/admin/requisites', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        },
        body: JSON.stringify(payload)
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok) {
        hint.textContent = data?.message ? `Ошибка: ${data.message}` : 'Ошибка';
        return;
      }

      hint.textContent = 'Добавлено';
      document.getElementById('reqTitle').value = '';
      document.getElementById('reqNetwork').value = '';
      document.getElementById('reqDetails').value = '';
      await loadRequisites();
    }

    async function toggleReq(id) {
      await fetch(`/admin/requisites/${id}/toggle`, {
        method: 'POST',
        headers: {
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        }
      });
      await loadRequisites();
    }

    async function loadTopups() {
      const status = document.getElementById('topupStatus').value;
      const url = new URL('/admin/topups', window.location.origin);
      if (status && status !== 'all') url.searchParams.set('status', status);

      const res = await fetch(url.toString(), {
        headers: {
          'Accept': 'application/json'
        }
      });
      const data = await res.json();

      const paginator = data.topups || data;
      adminState.topupsPaginator = paginator;
      adminState.topups = paginator.data || [];

      renderTopups();
    }

    function renderTopups() {
      const tbody = document.getElementById('topupsTbody');
      tbody.innerHTML = '';

      for (const t of adminState.topups) {
        const tr = document.createElement('tr');
        const disabled = t.status !== 'pending';

        tr.innerHTML = `
        <td class="mono">${t.id}</td>
        <td>${t.operator_id}</td>
        <td>${t.amount_usdt}</td>
        <td><span class="badge ${badgeClass(t.status)}">${t.status}</span></td>
        <td>${t.operator_comment ?? '—'}</td>
        <td>
          <input data-admin-comment="${t.id}" type="text" placeholder="коммент админа" value="${(t.admin_comment ?? '').replaceAll('"','&quot;')}" />
        </td>
        <td class="mono">${t.created_at ?? '—'}</td>
        <td>
          <div style="display:flex; gap:8px; flex-wrap:wrap;">
            <button class="btn ok" data-approve="${t.id}" ${disabled ? 'disabled' : ''}>Approve</button>
            <button class="btn danger" data-reject="${t.id}" ${disabled ? 'disabled' : ''}>Reject</button>
          </div>
        </td>
      `;

        tr.querySelector(`button[data-approve="${t.id}"]`)?.addEventListener('click', () => approveTopup(t.id));
        tr.querySelector(`button[data-reject="${t.id}"]`)?.addEventListener('click', () => rejectTopup(t.id));
        tbody.appendChild(tr);
      }

      const meta = document.getElementById('topupsMeta');
      const p = adminState.topupsPaginator;
      meta.textContent = p ? `Всего: ${p.total}. Показано: ${adminState.topups.length}` : '—';
    }

    function getAdminCommentFor(id) {
      const input = document.querySelector(`input[data-admin-comment="${id}"]`);
      return input ? input.value : '';
    }

    async function approveTopup(id) {
      const admin_comment = getAdminCommentFor(id);

      await fetch(`/admin/topups/${id}/approve`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        },
        body: JSON.stringify({
          admin_comment,
          admin_id: 1
        })
      });

      await loadTopups();
      document.getElementById('adminHint').textContent = `Подтверждено #${id}`;
    }

    async function rejectTopup(id) {
      const admin_comment = getAdminCommentFor(id);

      await fetch(`/admin/topups/${id}/reject`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': csrfToken()
        },
        body: JSON.stringify({
          admin_comment,
          admin_id: 1
        })
      });

      await loadTopups();
      document.getElementById('adminHint').textContent = `Отклонено #${id}`;
    }

    document.getElementById('createReqBtn').onclick = createReq;
    document.getElementById('reloadTopupsBtn').onclick = loadTopups;
    document.getElementById('topupStatus').addEventListener('change', loadTopups);

    loadRequisites();
    loadTopups();
    setInterval(loadTopups, 5000);
  </script>
</body>

</html>