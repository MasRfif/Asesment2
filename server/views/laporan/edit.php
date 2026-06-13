<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="content-card form-panel fade-up">
    <h3>Edit Laporan Kerusakan</h3>
    <form action="index.php?page=update_laporan" method="POST" enctype="multipart/form-data" class="form-grid">
        <input type="hidden" name="id" value="<?= $laporan['id'] ?>">

<label>Lokasi Fasilitas</label>
<input type="text" name="lokasi_fasilitas" value="<?= htmlspecialchars($laporan['lokasi_fasilitas'] ?? '') ?>" placeholder="Contoh: Gedung A, Laboratorium, Toilet" required>
<label>Waktu Laporan</label>
<input type="datetime-local" name="waktu_laporan" value="<?= isset($laporan) ? date('Y-m-d\TH:i', strtotime($laporan['waktu_laporan'])) : '' ?>" required>
<label>Jumlah Fasilitas Rusak</label>
<input type="number" name="jumlah_fasilitas_rusak" min="1" value="<?= htmlspecialchars($laporan['jumlah_fasilitas_rusak'] ?? '') ?>" required>
<label>Deskripsi</label>
<textarea name="deskripsi" rows="5" required><?= htmlspecialchars($laporan['deskripsi'] ?? '') ?></textarea>
<label>Foto Bukti</label>
<input type="file" name="foto_bukti" accept=".jpg,.jpeg,.png" <?= isset($laporan) ? '' : 'required' ?>>

        <p class="hint">File lama: <?= htmlspecialchars($laporan['foto_bukti']) ?>. Kosongkan foto jika tidak ingin mengganti file.</p>
        <button type="submit">Update Laporan</button>
    </form>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
