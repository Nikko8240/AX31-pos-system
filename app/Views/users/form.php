<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
    $errors = session()->getFlashdata('errors');
    $isEditing = $user !== null;
    $action = $isEditing
        ? site_url('users/' . $user['id'])
        : site_url('users');
?>

<div class="card">
    <h2><?= $isEditing ? 'Edit Staff Member' : 'Add Staff Member' ?></h2>

    <?php if ($errors) : ?>
        <div class="alert error">
            <?php foreach ($errors as $error) : ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= $action ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <label for="username">Username</label>
        <input id="username" name="username" value="<?= esc(old('username', $user['username'] ?? '')) ?>" required>

        <label for="full_name">Full Name</label>
        <input id="full_name" name="full_name" value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>" required>

        <label for="password">
            Password<?= $isEditing ? ' (leave blank to keep the current password)' : '' ?>
        </label>
        <input id="password" name="password" type="password" <?= $isEditing ? '' : 'required' ?>>

        <label for="avatar">Avatar (JPG, PNG, or WebP; maximum 2 MB)</label>
        <input id="avatar" name="avatar" type="file" accept=".jpg,.jpeg,.png,.webp">

        <?php if ($isEditing && $user['avatar']) : ?>
            <p>
                <img class="thumbnail" src="<?= base_url('uploads/avatars/' . $user['avatar']) ?>" alt="<?= esc($user['full_name']) ?>">
            </p>
        <?php endif; ?>

        <button class="button" type="submit"><?= $isEditing ? 'Save Changes' : 'Create Staff Member' ?></button>
        <a class="button" href="<?= site_url('users') ?>">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>