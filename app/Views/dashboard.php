<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <h2>Dashboard</h2>
    <p class="muted">Overview of current inventory and today's sales.</p>

    <table>
        <tbody>
            <tr>
                <th>Total Products</th>
                <td><?= esc($productCount) ?></td>
            </tr>
            <tr>
                <th>Today's Sales</th>
                <td>PHP <?= number_format((float) $todaySales, 2) ?></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="card" style="margin-top: 24px;">
    <h2>Low Stock Products</h2>

    <?php if ($lowStockProducts === []) : ?>
        <p class="muted">No products are currently low in stock.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Available Stock</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($lowStockProducts as $product) : ?>
                    <tr>
                        <td><?= esc($product['name']) ?></td>
                        <td><?= esc($product['stock_quantity']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>