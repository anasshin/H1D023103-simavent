<main class="d-flex align-items-center py-4 bg-body-tertiary min-vh-100">
    <div class="card p-4 shadow m-auto" style="width: 100%; max-width: 400px; border-radius: 1rem;">
        <h4 class="mb-3 text-center fw-bold">Register</h4>
        <p class="text-center ">Silakan buat akun untuk melanjutkan</p>

        <!-- Flash Message -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="alert alert-danger"><?php echo $this->session->flashdata('error'); ?></div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('success')): ?>
            <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
        <?php endif; ?>

        <form action="<?= base_url('auth/register') ?>" method="post">
            <div class="mb-3">
                <label for="name" class="form-label">Nama</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Kata Sandi</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Buat Akun</button>
        </form>

        <div class="mt-3 text-center text-secondary">
            Sudah punya akun? <a href="/auth/login" class="fw-bold">Masuk</a>
        </div>
    </div>
</main>