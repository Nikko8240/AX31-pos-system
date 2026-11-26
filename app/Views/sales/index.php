<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <div>
            <h2>Sales History</h2>
            <p class="muted">All recorded sales transactions.</p>
        </div>
        <a class="button" href="<?= site_url('sales/new') ?>">Record Sale</a>
    </div>

    <?php if ($sales === []) : ?>
        <p class="muted">No sales have been recorded yet.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Customer</th>
                    <th>Sold By</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($sales as $sale) : ?>
                    <tr>
                        <td><?= esc($sale['product_name']) ?></td>
                        <td><?= esc($sale['customer_name'] ?? 'Walk-in customer') ?></td>
                        <td><?= esc($sale['staff_name']) ?></td>
                        <td><?= esc($sale['quantity']) ?></td>
                        <td>PHP <?= number_format((float) $sale['total_price'], 2) ?></td>
                        <td><?= esc($sale['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?= $this->endSection() ?>