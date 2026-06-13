<?php
require_once __DIR__ . '/../models/Laporan.php';

class LaporanController {
    private $laporan;

    public function __construct($conn) {
        $this->laporan = new Laporan($conn);
    }

    private function wajibLogin() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }
    }

    private function uploadFoto($wajib = true) {
        if (!isset($_FILES['foto_bukti']) || $_FILES['foto_bukti']['error'] == 4) {
            return $wajib ? false : null;
        }
        $file = $_FILES['foto_bukti'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $izin = ['jpg', 'jpeg', 'png'];
        if (!in_array($ext, $izin)) return false;
        $namaBaru = 'bukti_' . date('YmdHis') . '_' . rand(100,999) . '.' . $ext;
        $tujuan = __DIR__ . '/../uploads/' . $namaBaru;
        return move_uploaded_file($file['tmp_name'], $tujuan) ? $namaBaru : false;
    }

    public function index() {
        $this->wajibLogin();
        $title = "Data Laporan";
        $data = $this->laporan->allByUser($_SESSION['user']['id']);
        include __DIR__ . '/../views/laporan/index.php';
    }

    public function tambah() {
        $this->wajibLogin();
        $title = "Tambah Laporan";
        include __DIR__ . '/../views/laporan/tambah.php';
    }

    public function edit() {
        $this->wajibLogin();
        $title = "Edit Laporan";
        $id = (int)($_GET['id'] ?? 0);
        $laporan = $this->laporan->findByUser($id, $_SESSION['user']['id']);
        if (!$laporan) {
            header("Location: index.php?page=laporan");
            exit;
        }
        include __DIR__ . '/../views/laporan/edit.php';
    }

    public function simpan() {
        $this->wajibLogin();
        $lokasi = trim($_POST['lokasi_fasilitas'] ?? '');
        $waktu = $_POST['waktu_laporan'] ?? '';
        $jumlah = (int)($_POST['jumlah_fasilitas_rusak'] ?? 0);
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $foto = $this->uploadFoto(true);

        if ($lokasi == '' || $waktu == '' || $jumlah < 1 || $deskripsi == '' || !$foto) {
            $_SESSION['error'] = "Data belum lengkap atau foto tidak sesuai.";
            header("Location: index.php?page=tambah_laporan");
            exit;
        }

        $this->laporan->create($_SESSION['user']['id'], $lokasi, $waktu, $jumlah, $deskripsi, $foto);
        $_SESSION['success'] = "Laporan berhasil ditambahkan.";
        header("Location: index.php?page=laporan");
        exit;
    }

    public function update() {
        $this->wajibLogin();
        $id = (int)($_POST['id'] ?? 0);
        $lokasi = trim($_POST['lokasi_fasilitas'] ?? '');
        $waktu = $_POST['waktu_laporan'] ?? '';
        $jumlah = (int)($_POST['jumlah_fasilitas_rusak'] ?? 0);
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        $foto = $this->uploadFoto(false);

        if ($lokasi == '' || $waktu == '' || $jumlah < 1 || $deskripsi == '' || $foto === false) {
            $_SESSION['error'] = "Data belum lengkap atau foto tidak sesuai.";
            header("Location: index.php?page=edit_laporan&id=" . $id);
            exit;
        }

        $lama = $this->laporan->findByUser($id, $_SESSION['user']['id']);
        $this->laporan->update($id, $_SESSION['user']['id'], $lokasi, $waktu, $jumlah, $deskripsi, $foto);
        if ($foto && $lama && $lama['foto_bukti']) {
            $path = __DIR__ . '/../uploads/' . $lama['foto_bukti'];
            if (file_exists($path)) unlink($path);
        }
        $_SESSION['success'] = "Laporan berhasil diubah.";
        header("Location: index.php?page=laporan");
        exit;
    }

    public function hapus() {
        $this->wajibLogin();
        $id = (int)($_GET['id'] ?? 0);
        $this->laporan->delete($id, $_SESSION['user']['id']);
        $_SESSION['success'] = "Laporan berhasil dihapus.";
        header("Location: index.php?page=laporan");
        exit;
    }
}
?>
