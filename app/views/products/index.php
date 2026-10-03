<?php
$total_units = 0;
$stock_value = 0.0;
foreach ($products as $product) {
    $total_units += (int) $product['quantity'];
    $stock_value += (float) $product['price'] * (int) $product['quantity'];
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inventory · Forge Product Manager</title>
    <link rel="stylesheet" href="<?=site_url('lab6/styles.css?v=2')?>">
    <link rel="stylesheet" href="<?=site_url('lab6/premium.css?v=2')?>">
</head>
<body>
<main class="shell">
    <header class="top">
        <div class="brand"><div class="mark">F</div><div><div class="eyebrow">FORGE&nbsp; / &nbsp;INVENTORY</div><h1>Product Manager</h1></div></div>
        <div class="header-actions"><span class="session-label"><span class="status-dot"></span>WORKSPACE ACTIVE</span>
            <form method="post" action="<?=site_url('logout')?>"><?php csrf_field(); ?><button class="btn light logout" type="submit">Log out</button></form>
        </div>
    </header>
    <section class="toolbar"><div class="heading"><div class="eyebrow">PRODUCT MANAGEMENT&nbsp; / &nbsp;OVERVIEW</div><h2>Inventory, in focus.</h2><p>Manage your catalog and keep every detail in order.</p></div></section>
    <section class="stats">
        <article class="stat-card card"><span class="stat-label">TOTAL PRODUCTS</span><strong><?=str_pad((string) count($products), 2, '0', STR_PAD_LEFT)?></strong><span class="stat-note">Active catalog items</span></article>
        <article class="stat-card card"><span class="stat-label">UNITS IN STOCK</span><strong><?=number_format($total_units)?></strong><span class="stat-note">Across all products</span></article>
        <article class="stat-card card stat-value"><span class="stat-label">STOCK VALUE</span><strong>₱<?=number_format($stock_value, 2)?></strong><span class="stat-note">Based on current quantities</span></article>
    </section>
    <div class="layout">
        <section class="card table-card">
            <div class="table-heading"><div><div class="eyebrow">CATALOG</div><h3>Product list</h3></div><span class="count"><?=count($products)?> <?=count($products) === 1 ? 'ITEM' : 'ITEMS'?></span></div>
            <?php if ($products): ?>
            <div class="table-scroll"><table>
                <thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Actions</th></tr></thead>
                <tbody><?php foreach ($products as $product): ?>
                    <tr>
                        <td><strong><?=htmlspecialchars((string) $product['product_name'], ENT_QUOTES, 'UTF-8')?></strong><div class="desc"><?=htmlspecialchars((string) ($product['description'] ?? '') ?: 'No description', ENT_QUOTES, 'UTF-8')?></div></td>
                        <td>₱<?=number_format((float) $product['price'], 2)?></td>
                        <td><?=number_format((int) $product['quantity'])?></td>
                        <td><div class="actions"><a class="btn light small" href="<?=site_url('products/edit/'.(int) $product['id'])?>">Edit</a><form method="post" action="<?=site_url('products/delete/'.(int) $product['id'])?>"><?php csrf_field(); ?><button class="btn danger small" type="submit">Delete</button></form></div></td>
                    </tr>
                <?php endforeach ?></tbody>
            </table></div>
            <?php else: ?>
            <div class="empty"><div class="empty-mark">F</div><strong>Your catalog starts here</strong><p>Add a product to begin building your inventory.</p></div>
            <?php endif ?>
        </section>
        <aside class="card form-card">
            <div class="form-kicker eyebrow">GET STARTED</div><h3>Build your catalog</h3><p class="form-copy">Add a product and keep your inventory organized.</p>
            <a class="btn" href="<?=site_url('products/create')?>">Add a product</a>
        </aside>
    </div>
    <p class="footer">SECURE INVENTORY WORKSPACE&nbsp; · &nbsp;PRODUCT DATA PROTECTED BY LAVALUST</p>
</main>
</body>
</html>
