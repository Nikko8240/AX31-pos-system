<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; gap: 16px;">
        <div>
            <h2>Staff</h2>
            <p class="muted">Manage staff accounts that can access the POS.</p>
        </div>
        <a class="button" href="<?= site_url('users/new') ?>">Add Staff Member</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Avatar</th>
                <th>Username</th>
                <th>Full Name</th>
                <th>Created</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user) : ?>
                <tr>
                    <td>
                        <?php if ($user['avatar']) : ?>
                            <img class="thumbnail" src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>" alt="<?= esc($user['full_name']) ?>">
                        <?php else : ?>
                            <span class="muted">No avatar</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($user['username']) ?></td>
                    <td><?= esc($user['full_name']) ?></td>
                    <td><?= esc($user['created_at']) ?></td>
                    <td>
                        <div class="actions">
                            <a class="button" href="<?= site_url('users/' . $user['id'] . '/edit') ?>">Edit</a>
                            <form action="<?= site_url('users/' . $user['id'] . '/delete') ?>" method="post" onsubmit="return confirm('Delete this staff member?');">
                                <?= csrf_field() ?>
                                <button class="button danger" type="submit">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>