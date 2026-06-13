<?php include __DIR__ . '/../layout/header.php'; ?>
<section class="hero-panel fade-up">
    <div>
        <span class="eyebrow">Dashboard Operator</span>
        <h2>Halo, <?= htmlspecialchars($user['nama']) ?></h2>
        <p>Pantau jumlah laporan kerusakan fasilitas kampus berdasarkan status penanganan awal.</p>
    </div>
    <a href="index.php?page=tambah_laporan" class="primary-link">Buat Laporan</a>
</section>

<section class="stats-grid">
    <div class="stat-card"><span>Total Laporan</span><strong><?= $stats['total'] ?></strong><small>Semua laporan milik user</small></div>
    <div class="stat-card"><span>Ringan</span><strong><?= $stats['Ringan'] ?></strong><small>Jumlah rusak 1 sampai 2</small></div>
    <div class="stat-card"><span>Sedang</span><strong><?= $stats['Sedang'] ?></strong><small>Jumlah rusak 3 sampai 5</small></div>
    <div class="stat-card"><span>Berat</span><strong><?= $stats['Berat'] ?></strong><small>Jumlah rusak lebih dari 5</small></div>
</section>

<section class="content-card fade-up">
    <div class="section-title">
        <h3>Laporan Terbaru</h3>
        <a href="index.php?page=laporan">Lihat Semua</a>
    </div>
    <div class="table-wrap">
        <table>
            <thead><tr><th>Lokasi</th><th>Waktu</th><th>Jumlah</th><th>Status</th><th>Deskripsi</th></tr></thead>
            <tbody>
            <?php foreach($terbaru as $row): ?>
                <tr>
                    <td><?= htmlspecialchars($row['lokasi_fasilitas']) ?></td>
                    <td><?= htmlspecialchars($row['waktu_laporan']) ?></td>
                    <td><?= htmlspecialchars($row['jumlah_fasilitas_rusak']) ?></td>
                    <td><span class="badge <?= strtolower($row['status_kerusakan']) ?>"><?= $row['status_kerusakan'] ?></span></td>
                    <td><?= htmlspecialchars($row['deskripsi']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>
<?php include __DIR__ . '/../layout/footer.php'; ?>
