<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php $errors = session()->getFlashdata('errors'); ?>

<div class="card" style="max-width: 700px;">
    <h2>Record Sale</h2>
    <p class="muted">Stock is reduced only after a valid sale is recorded.</p>

    <?php if ($errors) : ?>
        <div class="alert error">
            <?php foreach ($errors as $error) : ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if ($products === []) : ?>
        <p class="muted">There are no products with available stock.</p>
        <a class="button" href="<?= site_url('products/new') ?>">Add Product</a>
    <?php else : ?>
        <form action="<?= site_url('sales') ?>" method="post">
            <?= csrf_field() ?>

            <label for="product_id">Product</label>
            <select id="product_id" name="product_id" required>
                <option value="">Select a product</option>
                <?php foreach ($products as $product) : ?>
                    <option value="<?= esc($product['id']) ?>" <?= old('product_id') == $product['id'] ? 'selected' : '' ?>>
                        <?= esc($product['name']) ?> -
                        PHP <?= number_format((float) $product['price'], 2) ?> -
                        <?= esc($product['stock_quantity']) ?> in stock
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="customer_id">Customer (optional)</label>
            <select id="customer_id" name="customer_id">
                <option value="">Walk-in customer</option>
                <?php foreach ($customers as $customer) : ?>
                    <option value="<?= esc($customer['id']) ?>" <?= old('customer_id') == $customer['id'] ? 'selected' : '' ?>>
                        <?= esc($customer['full_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="quantity">Quantity</label>
            <input id="quantity" name="quantity" type="number" min="1" value="<?= esc(old('quantity', 1)) ?>" required>

            <button class="button" type="submit">Record Sale</button>
        </form>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>