<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestion des Tâches</title>
    <style>
        :root { --bg: #f0f4ff; --card: #fff; --text: #1e293b; --muted: #64748b; --accent: #4f46e5; --accent-h: #4338ca; --danger: #ef4444; --ok: #10b981; --warn: #f59e0b; }
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: system-ui, sans-serif; color: var(--text); background: linear-gradient(135deg, #e0e7ff 0%, var(--bg) 50%, #fce7f3 100%); }
        .wrap { max-width: 900px; margin: 0 auto; padding: 2rem 1.25rem; }
        header h1 { margin: 0; font-size: 1.75rem; font-weight: 700; letter-spacing: -0.02em; }
        header p { margin: 0.35rem 0 0; color: var(--muted); font-size: 0.95rem; }
        .card { margin-top: 1.5rem; background: var(--card); border-radius: 16px; padding: 1.5rem; box-shadow: 0 10px 40px rgba(79, 70, 229, 0.08); }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e2e8f0; vertical-align: top; }
        .col-titre { width: 22%; }
        .col-desc { width: 38%; }
        .col-statut { width: 14%; }
        .col-actions { width: 26%; }
        .cell-titre, .cell-desc { overflow-wrap: anywhere; word-break: break-word; }
        .cell-desc { color: var(--muted); font-size: 0.9rem; line-height: 1.4; }
        .cell-actions { white-space: nowrap; }
        th { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.05em; color: var(--muted); font-weight: 600; }
        tr:hover td { background: #f8fafc; }
        .btn { display: inline-block; padding: 0.5rem 1rem; border: none; border-radius: 8px; font-size: 0.875rem; font-weight: 600; cursor: pointer; text-decoration: none; transition: transform 0.15s, box-shadow 0.15s; }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .btn-blue { background: var(--accent); color: #fff; }
        .btn-blue:hover { background: var(--accent-h); color: #fff; }
        .btn-red { background: var(--danger); color: #fff; }
        .success { background: #d1fae5; color: #065f46; padding: 0.75rem 1rem; border-radius: 8px; margin-bottom: 1rem; }
        .badge { display: inline-block; padding: 0.2rem 0.65rem; border-radius: 999px; font-size: 0.8rem; font-weight: 600; }
        .badge-done { background: #d1fae5; color: #047857; }
        .badge-pending { background: #fef3c7; color: #b45309; }
        .toolbar { margin-bottom: 1.25rem; }
        h2 { margin: 0 0 1rem; font-size: 1.25rem; }
        label { font-weight: 600; font-size: 0.9rem; }
        input[type=text], textarea { width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #e2e8f0; border-radius: 8px; margin-top: 0.35rem; font: inherit; }
        input:focus, textarea:focus { outline: 2px solid var(--accent); outline-offset: 1px; border-color: var(--accent); }
        .form-actions { margin-top: 1.25rem; display: flex; gap: 0.75rem; align-items: center; flex-wrap: wrap; }
        .empty { text-align: center; color: var(--muted); padding: 2rem; }
    </style>
</head>
<body>
    <div class="wrap">
        <header>
            <h1>Gestion des Tâches</h1>
            <p>Organisez vos tâches simplement</p>
        </header>
        <main class="card">
            @yield('contenu')
        </main>
    </div>
</body>
</html>
