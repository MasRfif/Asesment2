<?php
session_start();
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/LaporanController.php';

$page = $_GET['page'] ?? 'login';
$auth = new AuthController($conn);
$dashboard = new DashboardController($conn);
$laporan = new LaporanController($conn);

switch ($page) {
    case 'login': $auth->loginPage(); break;
    case 'register': $auth->registerPage(); break;
    case 'proses_login': $auth->login(); break;
    case 'proses_register': $auth->register(); break;
    case 'logout': $auth->logout(); break;
    case 'dashboard': $dashboard->index(); break;
    case 'laporan': $laporan->index(); break;
    case 'tambah_laporan': $laporan->tambah(); break;
    case 'edit_laporan': $laporan->edit(); break;
    case 'simpan_laporan': $laporan->simpan(); break;
    case 'update_laporan': $laporan->update(); break;
    case 'hapus_laporan': $laporan->hapus(); break;
    default: $auth->loginPage(); break;
}
?>
