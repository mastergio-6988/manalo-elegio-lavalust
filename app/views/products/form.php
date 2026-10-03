<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=htmlspecialchars($title, ENT_QUOTES, 'UTF-8')?> · Product Manager</title>
    <link rel="stylesheet" href="<?=site_url('lab6/styles.css?v=3')?>">
</head>
<body>
<main class="shell">
    <header class="top"><div class="brand"><div class="mark">F</div><div><div class="eyebrow">FORGE&nbsp; / &nbsp;INVENTORY</div><h1>Product Manager</h1></div></div><a class="btn light" href="<?=site_url('products')?>">Back to inventory</a></header>
    <section class="card form-card" style="max-width:620px;margin:0 auto">
        <div class="form-kicker eyebrow">CATALOG DETAILS</div><h3><?=htmlspecialchars($title, ENT_QUOTES, 'UTF-8')?></h3><p class="form-copy">Keep product details accurate and up to date.</p>
        <?php if($error): ?><p class="alert"><?=htmlspecialchars($error, ENT_QUOTES, 'UTF-8')?></p><?php endif ?>
        <form method="post" action="<?=site_url($action)?>" onsubmit="return confirm('Are you sure you want to save this product?');">
            <?php csrf_field(); ?>
            <div class="field"><label for="product_name">Product name</label><input class="input" id="product_name" name="product_name" maxlength="100" required placeholder="e.g. Leather weekender" value="<?=htmlspecialchars((string) ($product['product_name'] ?? ''), ENT_QUOTES, 'UTF-8')?>"></div>
            <div class="field"><label for="description">Description</label><textarea class="input" id="description" name="description" placeholder="Materials, details, and notes"><?=htmlspecialchars((string) ($product['description'] ?? ''), ENT_QUOTES, 'UTF-8')?></textarea></div>
            <div class="form-row"><div class="field"><label for="price">Price (PHP)</label><input class="input" id="price" name="price" type="number" min="0" step="0.01" required placeholder="0.00" value="<?=htmlspecialchars((string) ($product['price'] ?? ''), ENT_QUOTES, 'UTF-8')?>"></div>
            <div class="field"><label for="quantity">Quantity</label><input class="input" id="quantity" name="quantity" type="number" min="0" step="1" required placeholder="0" value="<?=htmlspecialchars((string) ($product['quantity'] ?? ''), ENT_QUOTES, 'UTF-8')?>"></div></div>
            <div class="form-actions"><button class="btn" type="submit">Save product</button><a class="btn light" href="<?=site_url('products')?>">Cancel</a></div>
        </form>
    </section>
</main>
</body>
</html>
