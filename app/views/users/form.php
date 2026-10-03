<?php
$escape = static fn ($value): string => htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $escape($title) ?></title>
    <style>
        :root { --purple:#5b5fc7; --ink:#242424; --muted:#616161; --line:#e1dfdd; }
        * { box-sizing:border-box; }
        body { margin:0; min-width:320px; background:#f5f5f5; color:var(--ink); font:14px "Segoe UI",Arial,sans-serif; }
        main { width:min(100% - 32px, 640px); margin:56px auto; padding:30px; border:1px solid var(--line); border-radius:8px; background:#fff; box-shadow:0 2px 7px rgba(0,0,0,.05); }
        a { color:#4f52b4; text-decoration:none; } h1 { margin:20px 0 5px; font-size:27px; } p { color:var(--muted); } label { display:block; margin-top:18px; font-weight:600; }
        input { width:100%; margin-top:7px; padding:10px; border:1px solid #8a8886; border-radius:4px; font:inherit; } input:focus { outline:2px solid #c7c9ff; border-color:var(--purple); }
        .error { padding:10px; border-radius:4px; background:#fdf3f4; color:#a4262c; }.actions { display:flex; justify-content:flex-end; gap:9px; margin-top:25px; }
        .button { min-height:35px; padding:0 13px; border:1px solid #d1d1d1; border-radius:4px; background:#fff; color:#323130; font:inherit; font-weight:600; cursor:pointer; }.button.primary { border-color:var(--purple); background:var(--purple); color:#fff; }
    </style>
</head>
<body>
    <main>
        <a href="<?= site_url('users') ?>">← Back to users</a>
        <h1><?= $escape($title) ?></h1>
        <p>Enter the directory details below.</p>
        <?php if ($error): ?><p class="error"><?= $escape($error) ?></p><?php endif; ?>
        <form method="post" action="<?= site_url($action) ?>">
            <?php csrf_field(); ?>
            <label for="firstname">First name</label>
            <input id="firstname" name="firstname" maxlength="100" required value="<?= $escape($user['firstname'] ?? '') ?>">
            <label for="lastname">Last name</label>
            <input id="lastname" name="lastname" maxlength="100" required value="<?= $escape($user['lastname'] ?? '') ?>">
            <label for="email">Email address</label>
            <input id="email" type="email" name="email" maxlength="150" required value="<?= $escape($user['email'] ?? '') ?>">
            <label for="username">Username</label>
            <input id="username" name="username" maxlength="100" required value="<?= $escape($user['username'] ?? '') ?>">
            <div class="actions"><a class="button" href="<?= site_url('users') ?>">Cancel</a><button class="button primary" type="submit">Save user</button></div>
        </form>
    </main>
</body>
</html>
