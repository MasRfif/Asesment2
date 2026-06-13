<?php
class Laporan {
    private $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function tentukanStatus($jumlah) {
        if ($jumlah <= 2) return "Ringan";
        if ($jumlah <= 5) return "Sedang";
        return "Berat";
    }

    public function allByUser($userId) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM laporan WHERE user_id = ? ORDER BY waktu_laporan DESC");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    }

    public function latestByUser($userId) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM laporan WHERE user_id = ? ORDER BY waktu_laporan DESC LIMIT 5");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_all(mysqli_stmt_get_result($stmt), MYSQLI_ASSOC);
    }

    public function findByUser($id, $userId) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM laporan WHERE id = ? AND user_id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "ii", $id, $userId);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    public function find($id) {
        $stmt = mysqli_prepare($this->conn, "SELECT * FROM laporan WHERE id = ? LIMIT 1");
        mysqli_stmt_bind_param($stmt, "i", $id);
        mysqli_stmt_execute($stmt);
        return mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
    }

    public function create($userId, $lokasi, $waktu, $jumlah, $deskripsi, $foto) {
        $status = $this->tentukanStatus($jumlah);
        $stmt = mysqli_prepare($this->conn, "INSERT INTO laporan (user_id, lokasi_fasilitas, waktu_laporan, jumlah_fasilitas_rusak, status_kerusakan, deskripsi, foto_bukti) VALUES (?, ?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "ississs", $userId, $lokasi, $waktu, $jumlah, $status, $deskripsi, $foto);
        return mysqli_stmt_execute($stmt);
    }

    public function update($id, $userId, $lokasi, $waktu, $jumlah, $deskripsi, $foto) {
        $status = $this->tentukanStatus($jumlah);
        if ($foto) {
            $stmt = mysqli_prepare($this->conn, "UPDATE laporan SET lokasi_fasilitas=?, waktu_laporan=?, jumlah_fasilitas_rusak=?, status_kerusakan=?, deskripsi=?, foto_bukti=? WHERE id=? AND user_id=?");
            mysqli_stmt_bind_param($stmt, "ssisssii", $lokasi, $waktu, $jumlah, $status, $deskripsi, $foto, $id, $userId);
        } else {
            $stmt = mysqli_prepare($this->conn, "UPDATE laporan SET lokasi_fasilitas=?, waktu_laporan=?, jumlah_fasilitas_rusak=?, status_kerusakan=?, deskripsi=? WHERE id=? AND user_id=?");
            mysqli_stmt_bind_param($stmt, "ssissii", $lokasi, $waktu, $jumlah, $status, $deskripsi, $id, $userId);
        }
        return mysqli_stmt_execute($stmt) && mysqli_stmt_affected_rows($stmt) >= 0;
    }

    public function delete($id, $userId) {
        $data = $this->findByUser($id, $userId);
        if (!$data) return false;
        $stmt = mysqli_prepare($this->conn, "DELETE FROM laporan WHERE id=? AND user_id=?");
        mysqli_stmt_bind_param($stmt, "ii", $id, $userId);
        $hasil = mysqli_stmt_execute($stmt);
        if ($hasil && $data['foto_bukti'] && $data['foto_bukti'] != 'tanpa_foto.jpg') {
            $path = __DIR__ . "/../uploads/" . $data['foto_bukti'];
            if (file_exists($path)) unlink($path);
        }
        return $hasil;
    }

    public function stats($userId) {
        $data = ["total"=>0, "Ringan"=>0, "Sedang"=>0, "Berat"=>0];
        $stmt = mysqli_prepare($this->conn, "SELECT status_kerusakan, COUNT(*) jumlah FROM laporan WHERE user_id=? GROUP BY status_kerusakan");
        mysqli_stmt_bind_param($stmt, "i", $userId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $data[$row['status_kerusakan']] = (int)$row['jumlah'];
            $data['total'] += (int)$row['jumlah'];
        }
        return $data;
    }

    public function apiCreate($data) {
        return $this->create((int)$data['user_id'], $data['lokasi_fasilitas'], $data['waktu_laporan'], (int)$data['jumlah_fasilitas_rusak'], $data['deskripsi'], $data['foto_bukti'] ?? 'tanpa_foto.jpg');
    }

    public function apiUpdateByUser($id, $userId, $data) {
        $lama = $this->findByUser($id, $userId);
        if (!$lama) return false;
        $foto = $data['foto_bukti'] ?? null;
        return $this->update($id, $userId, $data['lokasi_fasilitas'], $data['waktu_laporan'], (int)$data['jumlah_fasilitas_rusak'], $data['deskripsi'], $foto);
    }
}
?>
