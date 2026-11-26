<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
    $errors = session()->getFlashdata('errors');
    $isEditing = $product !== null;
    $action = $isEditing
        ? site_url('products/' . $product['id'])
        : site_url('products');
?>

<div class="card">
    <h2><?= $isEditing ? 'Edit Product' : 'Add Product' ?></h2>

    <?php if ($errors) : ?>
        <div class="alert error">
            <?php foreach ($errors as $error) : ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <label for="name">Product Name</label>
        <input id="name" name="name" value="<?= esc(old('name', $product['name'] ?? '')) ?>" required>

        <label for="price">Price</label>
        <input id="price" name="price" type="number" min="0.01" step="0.01" value="<?= esc(old('price', $product['price'] ?? '')) ?>" required>

        <label for="stock_quantity">Stock Quantity</label>
        <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="<?= esc(old('stock_quantity', $product['stock_quantity'] ?? 0)) ?>" required>

        <label for="image">Product Image (JPG, PNG, or WebP; maximum 2 MB)</label>
        <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp">

        <?php if ($isEditing && $product['image']) : ?>
            <p>
                <img class="thumbnail" src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= esc($product['name']) ?>">
            </p>
        <?php endif; ?>

        <button class="button" type="submit"><?= $isEditing ? 'Save Changes' : 'Create Product' ?></button>
        <a class="button" href="<?= site_url('products') ?>">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>