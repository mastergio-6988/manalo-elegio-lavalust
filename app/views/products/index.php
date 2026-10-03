<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Products · Product Manager</title>
    <link rel="stylesheet" href="<?=site_url('lab6/styles.css?v=3')?>">
</head>
<body>
<main class="shell">
    <header class="top">
        <h1>Product Management</h1>
        <form method="post" action="<?=site_url('logout')?>"><?php csrf_field(); ?><button class="btn light logout" type="submit">Logout</button></form>
    </header>
    <section class="card form-card">
        <form class="product-form" method="post" action="<?=site_url('products/create')?>" onsubmit="return confirm('Are you sure you want to add this product?');">
            <?php csrf_field(); ?>
            <input class="input" name="product_name" maxlength="100" required aria-label="Product name" placeholder="Product name">
            <input class="input" name="description" aria-label="Description" placeholder="Description">
            <input class="input" name="price" type="number" min="0" step="0.01" required aria-label="Price" placeholder="Price">
            <input class="input" name="quantity" type="number" min="0" step="1" required aria-label="Quantity" placeholder="Qty">
            <button class="btn" type="submit">Add product</button>
        </form>
    </section>
    <section class="card table-card">
            <?php if ($products): ?>
            <div class="table-scroll"><table>
                <thead><tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Qty</th><th>Actions</th></tr></thead>
                <tbody><?php foreach ($products as $product): ?>
                    <tr>
                        <td><?= (int) $product['id'] ?></td><td><?=htmlspecialchars((string) $product['product_name'], ENT_QUOTES, 'UTF-8')?></td><td class="description-cell"><?=htmlspecialchars((string) ($product['description'] ?? '') ?: '—', ENT_QUOTES, 'UTF-8')?></td>
                        <td>₱<?=number_format((float) $product['price'], 2)?></td>
                        <td><?=number_format((int) $product['quantity'])?></td>
                        <td><div class="actions"><a class="btn edit small" href="<?=site_url('products/edit/'.(int) $product['id'])?>">Edit</a><form method="post" action="<?=site_url('products/delete/'.(int) $product['id'])?>" onsubmit="return confirm('Are you sure you want to delete this product?');"><?php csrf_field(); ?><button class="btn danger small" type="submit">Delete</button></form></div></td>
                    </tr>
                <?php endforeach ?></tbody>
            </table></div>
            <?php else: ?>
            <div class="empty">No products yet. Add your first product above.</div>
            <?php endif ?>
    </section>
</main>
</body>
</html>
