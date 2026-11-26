<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'POS System') ?></title>
    <style>
    :root {
        font-family: Arial, sans-serif;
        color: #f8fafc;
        background: #101827;
    }

    * { box-sizing: border-box; }

    body {
        margin: 0;
        min-height: 100vh;
        background:
            radial-gradient(circle at top right, #263650 0%, transparent 36%),
            #101827;
    }

    header {
        background: linear-gradient(90deg, #3f1118, #651a22);
        border-bottom: 3px solid #d6a62a;
        color: #fff7e5;
        padding: 16px 28px;
        box-shadow: 0 3px 12px #00000055;
    }

    header h1 {
        margin: 0;
        color: #f6cc57;
        font-size: 21px;
        letter-spacing: 0.4px;
    }

    nav {
        display: flex;
        flex-wrap: wrap;
        align-items: center;
        gap: 14px;
        margin-top: 12px;
    }

    nav a,
    .logout-button {
        color: #ffe8a3;
        text-decoration: none;
        font: inherit;
        background: none;
        border: 0;
        cursor: pointer;
        padding: 0;
    }

    nav a:hover,
    .logout-button:hover {
        color: #ffffff;
        text-decoration: underline;
    }

    .user {
        margin-left: auto;
        color: #f7d983;
    }

    .logout { display: inline; }

    main {
        max-width: 1100px;
        margin: 30px auto;
        padding: 0 20px;
    }

    .card {
        background: #182438;
        border: 1px solid #31415b;
        border-top: 3px solid #8d2029;
        border-radius: 10px;
        padding: 24px;
        box-shadow: 0 8px 22px #00000038;
    }

    h2 { color: #f6cc57; }

    .alert {
        padding: 12px 14px;
        border-radius: 6px;
        margin-bottom: 18px;
    }

    .success {
        background: #153b2c;
        border: 1px solid #2f8d62;
        color: #d1fae5;
    }

    .error {
        background: #4b1720;
        border: 1px solid #d35a65;
        color: #ffe0e3;
    }

    .button {
        display: inline-block;
        background: #d6a62a;
        color: #1a1304;
        border: 1px solid #f4d675;
        border-radius: 6px;
        padding: 9px 13px;
        text-decoration: none;
        cursor: pointer;
        font: inherit;
        font-weight: bold;
    }

    .button:hover { background: #f1c84e; }

    .button.danger {
        background: #8d2029;
        border-color: #c65762;
        color: #fff5f5;
    }

    .button.danger:hover { background: #b32c37; }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 18px;
    }

    th,
    td {
        border-bottom: 1px solid #31415b;
        padding: 11px 8px;
        text-align: left;
    }

    th {
        color: #f4d675;
        font-size: 13px;
        text-transform: uppercase;
    }

    td { color: #f8fafc; }

    input,
    select {
        width: 100%;
        padding: 9px;
        margin: 5px 0 14px;
        border: 1px solid #50617d;
        border-radius: 5px;
        background: #0e1726;
        color: #f8fafc;
        font: inherit;
    }

    input:focus,
    select:focus,
    .button:focus,
    nav a:focus,
    .logout-button:focus {
        outline: 3px solid #f6cc57;
        outline-offset: 2px;
    }

    label { color: #ffe8a3; font-weight: bold; }

    .actions {
        display: flex;
        gap: 8px;
    }

    .actions form { margin: 0; }

    .muted { color: #bdc9dc; }

    img.thumbnail {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border: 2px solid #d6a62a;
        border-radius: 6px;
    }

    @media (max-width: 700px) {
        .user {
            width: 100%;
            margin-left: 0;
        }

        table { font-size: 14px; }
    }
</style>
</head>
<body>
    <header>
        <h1>Point-of-Sale System</h1>

        <?php if (session()->get('isLoggedIn')) : ?>
            <nav>
                <a href="<?= site_url('/') ?>">Dashboard</a>
                <a href="<?= site_url('products') ?>">Products</a>
                <a href="<?= site_url('customers') ?>">Customers</a>
                <a href="<?= site_url('users') ?>">Staff</a>
                <a href="<?= site_url('sales/new') ?>">Record Sale</a>
                <a href="<?= site_url('sales') ?>">Sales History</a>
                <span class="user"><?= esc(session()->get('full_name')) ?></span>
                <form class="logout" action="<?= site_url('logout') ?>" method="post">
                    <?= csrf_field() ?>
                    <button class="logout-button" type="submit">Log out</button>
                </form>
            </nav>
        <?php endif; ?>
    </header>

    <main>
        <?php if (session()->getFlashdata('success')) : ?>
            <div class="alert success"><?= esc(session()->getFlashdata('success')) ?></div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('error')) : ?>
            <div class="alert error"><?= esc(session()->getFlashdata('error')) ?></div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>
</body>
</html>