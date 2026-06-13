<?php
require_once __DIR__ . '/../models/Laporan.php';

class DashboardController {
    private $laporan;

    public function __construct($conn) {
        $this->laporan = new Laporan($conn);
    }

    public function index() {
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?page=login");
            exit;
        }
        $title = "Dashboard";
        $user = $_SESSION['user'];
        $stats = $this->laporan->stats($user['id']);
        $terbaru = $this->laporan->latestByUser($user['id']);
        include __DIR__ . '/../views/dashboard/index.php';
    }
}
?>
