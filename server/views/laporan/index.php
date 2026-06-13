<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="content-card fade-up">
    <div class="section-title">
        <h3>Daftar Laporan Kerusakan</h3>
        <a href="index.php?page=tambah_laporan" class="primary-link">Tambah</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr><th>Lokasi</th><th>Waktu</th><th>Jumlah</th><th>Status</th><th>Deskripsi</th><th>File Bukti</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php foreach($data as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['lokasi_fasilitas']) ?></td>
                    <td><?= htmlspecialchars($row['waktu_laporan']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah_fasilitas_rusak']) ?></td>
                    <td><span class="badge <?= strtolower($row['status_kerusakan']) ?>"><?= $row['status_kerusakan'] ?></span></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                    <td><a href="uploads/<?= htmlspecialchars($row['foto_bukti']) ?>" target="_blank"><?= htmlspecialchars($row['foto_bukti']) ?></a></td>
                    <td class="actions">
                        <a href="index.php?page=edit_laporan&id=<?= $row['id'] ?>">Edit</a>
                        <a class="danger" onclick="return confirm('Hapus laporan ini?')" href="index.php?page=hapus_laporan&id=<?= $row['id'] ?>">Hapus</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
