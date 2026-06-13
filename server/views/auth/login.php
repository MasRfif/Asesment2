<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="auth-card fade-up">
    <h2>Masuk Akun Operator</h2>
    <p>Gunakan akun operator untuk mengelola laporan fasilitas.</p>
    <form action="index.php?page=proses_login" method="POST" class="form-grid">
        <label>Email</label>
        <input type="email" name="email" placeholder="operator@gmail.com" required>
        <label>Password</label>
        <input type="password" name="password" placeholder="Minimal 6 karakter" required>
        <button type="submit">Login</button>
    </form>
    <p class="form-link">Belum punya akun? <a href="index.php?page=register">Daftar di sini</a></p>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
