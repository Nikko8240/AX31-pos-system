<?= $this->extend('layouts/app') ?>

<?= $this->section('content') ?>
<div class="card" style="max-width: 430px; margin: 50px auto;">
    <h2>Staff Login</h2>
    <p class="muted">Sign in to manage products, customers, staff, and sales.</p>

    <?php $errors = session()->getFlashdata('errors'); ?>
    <?php if ($errors) : ?>
        <div class="alert error">
            <?php foreach ($errors as $error) : ?>
                <div><?= esc($error) ?></div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="<?= site_url('login') ?>" method="post">
        <?= csrf_field() ?>

        <label for="username">Username</label>
        <input id="username" name="username" value="<?= esc(old('username')) ?>" required>

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required>

        <button class="button" type="submit">Log in</button>
    </form>
</div>
<?= $this->endSection() ?>