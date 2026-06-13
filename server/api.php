<?php
require_once __DIR__ . '/config/koneksi.php';
require_once __DIR__ . '/controllers/ApiLaporanController.php';
$api = new ApiLaporanController($conn);
$api->handle();
?>
