<!doctype html>
<html lang="ru">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Вход по токену</title>
  <style>
    :root {
      --bg: #0b1220;
      --card: #111a2e;
      --border: #1f2a44;
      --text: #e8eefc;
      --muted: #8fa3c8;
      --green: #21c36b;
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
      max-width: 520px;
      margin: 0 auto;
      padding: 18px;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }

    .card {
      width: 100%;
      background: var(--card);
      border: 1px solid var(--border);
      border-radius: 14px;
      padding: 16px;
    }

    .title {
      font-size: 18px;
      font-weight: 900;
      margin: 0 0 10px;
    }

    .hint {
      color: var(--muted);
      font-size: 12px;
      line-height: 1.35;
    }

    input[type="password"],
    input[type="text"] {
      width: 100%;
      box-sizing: border-box;
      margin-top: 10px;
      background: rgba(255, 255, 255, 0.03);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: var(--text);
      border-radius: 10px;
      padding: 12px 12px;
      outline: none;
    }

    .btn {
      margin-top: 12px;
      width: 100%;
      background: rgba(96, 165, 250, 0.15);
      border: 1px solid rgba(96, 165, 250, 0.25);
      color: var(--blue);
      border-radius: 10px;
      padding: 12px 12px;
      cursor: pointer;
      font-weight: 900;
    }

    .msg {
      margin-top: 10px;
      font-size: 13px;
    }

    .ok {
      color: var(--green);
    }

    .err {
      color: var(--red);
    }

    .row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
      margin-top: 10px;
    }

    .linkbtn {
      width: auto;
      background: rgba(255, 255, 255, 0.04);
      border: 1px solid rgba(255, 255, 255, 0.12);
      color: var(--text);
      border-radius: 10px;
      padding: 10px 12px;
      cursor: pointer;
      font-weight: 800;
    }

    .mono {
      font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono";
      font-size: 12px;
      color: var(--muted);
    }
  </style>
</head>

<body>
  <div class="wrap">
    <div class="card">
      <div class="title">Вход по токену</div>
      <div class="hint">
        Введите токен доступа. Токен оператора откроет <span class="mono">/operator</span>,
        токен админа — <span class="mono">/admin/dashboard</span>.
      </div>

      @if (session('error'))
      <div class="msg err">{{ session('error') }}</div>
      @endif
      @if (session('success'))
      <div class="msg ok">{{ session('success') }}</div>
      @endif

      <form method="POST" action="/login">
        @csrf
        <input name="token" type="password" placeholder="Введите токен..." autocomplete="off" />
        <button class="btn" type="submit">Войти</button>
      </form>

      <form method="POST" action="/logout">
        @csrf
        <div class="row">
          <div class="hint">Если роль уже в сессии — можно выйти.</div>
          <button class="linkbtn" type="submit">Выйти</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>