<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <div>
            <h2>Customers</h2>
            <p class="muted">Manage customer records for sales transactions.</p>
        </div>
        <a class="button" href="<?= site_url('customers/new') ?>">Add Customer</a>
    </div>

    <?php if ($customers === []) : ?>
        <p class="muted">No customers have been added yet.</p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $customer) : ?>
                    <tr>
                        <td><?= esc($customer['full_name']) ?></td>
                        <td><?= esc($customer['email']) ?></td>
                        <td><?= esc($customer['phone'] ?: '-') ?></td>
                        <td>
                            <div class="actions">
                                <a class="button" href="<?= site_url('customers/' . $customer['id'] . '/edit') ?>">Edit</a>
                                <form action="<?= site_url('customers/' . $customer['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this customer?');">
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