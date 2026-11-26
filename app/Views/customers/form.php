<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<?php
    $errors = session()->getFlashdata('errors');
    $isEditing = $customer !== null;
    $action = $isEditing
        ? site_url('customers/' . $customer['id'])
        : site_url('customers');
?>

<div class="card">
    <h2><?= $isEditing ? 'Edit Customer' : 'Add Customer' ?></h2>

    <?php if ($errors) : ?>
        <div class="alert error">
            <?php foreach ($errors as $error) : ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= $action ?>" method="post">
        <?= csrf_field() ?>

        <label for="full_name">Full Name</label>
        <input id="full_name" name="full_name" value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>" required>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?= esc(old('email', $customer['email'] ?? '')) ?>" required>

        <label for="phone">Phone</label>
        <input id="phone" name="phone" value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>">

        <button class="button" type="submit"><?= $isEditing ? 'Save Changes' : 'Create Customer' ?></button>
        <a class="button" href="<?= site_url('customers') ?>">Cancel</a>
    </form>
</div>
<?= $this->endSection() ?>