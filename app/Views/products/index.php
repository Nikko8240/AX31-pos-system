<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <div>
            <h2>Products</h2>
            <p class="muted">Manage available products and inventory quantities.</p>
        </div>
        <a class="button" href="<?= site_url('products/new') ?>">Add Product</a>
    </div>

    <?php if ($products === []) : ?>
        <p class="muted">No products have been added yet.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Stock</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product) : ?>
                    <tr>
                        <td>
                            <?php if ($product['image']) : ?>
                                <img class="thumbnail" src="<?= base_url('uploads/products/' . $product['image']) ?>" alt="<?= esc($product['name']) ?>">
                            <?php else : ?>
                                <span class="muted">No image</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($product['name']) ?></td>
                        <td>PHP <?= number_format((float) $product['price'], 2) ?></td>
                        <td><?= esc($product['stock_quantity']) ?></td>
                        <td>
                            <div class="actions">
                                <a class="button" href="<?= site_url('products/' . $product['id'] . '/edit') ?>">Edit</a>
                                <form action="<?= site_url('products/' . $product['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this product?');">
                                    <?= csrf_field() ?>
                                    <button class="button danger" type="submit">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>