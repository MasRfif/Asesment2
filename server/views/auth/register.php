<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="auth-card fade-up">
    <h2>Registrasi Operator</h2>
    <p>Buat akun untuk mulai membuat laporan kerusakan fasilitas.</p>
    <form action="index.php?page=proses_register" method="POST" class="form-grid">
        <label>Nama</label>
        <input type="text" name="nama" required>
        <label>Email</label>
        <input type="email" name="email" required>
        <label>Password</label>
        <input type="password" name="password" minlength="6" required>
        <button type="submit">Daftar</button>
    </form>
    <p class="form-link">Sudah punya akun? <a href="index.php?page=login">Login</a></p>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
