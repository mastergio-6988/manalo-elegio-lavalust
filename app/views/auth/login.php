<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · Forge Product Manager</title>
    <link rel="stylesheet" href="<?=site_url('lab6/styles.css?v=2')?>">
    <link rel="stylesheet" href="<?=site_url('lab6/premium.css?v=2')?>">
</head>
<body>
    <main class="login card">
        <div class="brand">
            <div class="mark">F</div>
            <div><div class="eyebrow">FORGE&nbsp; / &nbsp;INVENTORY</div><h1>Product Manager</h1></div>
        </div>
        <div class="login-intro">
            <div class="eyebrow">YOUR PRODUCT WORKSPACE</div>
            <h2>A clearer view of your inventory.</h2>
            <p class="muted">Sign in to manage products, stock, and pricing from one place.</p>
        </div>
        <?php if($error): ?><p class="alert"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></p><?php endif ?>
        <form method="post" action="<?=site_url('login')?>">
            <?php csrf_field(); ?>
            <div class="field"><label for="username">Username</label><input class="input" id="username" name="username" placeholder="Enter your username" autocomplete="username" required></div>
            <div class="field"><label for="password">Password</label><input class="input" id="password" name="password" type="password" placeholder="Enter your password" autocomplete="current-password" required></div>
            <button class="btn login-submit" type="submit">Sign in to workspace</button>
        </form>
        <div class="login-foot"><span class="status-dot"></span>SECURE ACCESS&nbsp; · &nbsp;LAVALUST</div>
    </main>
</body>
</html>
