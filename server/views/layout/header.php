<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SmartCampus Facility' ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar">
        <div class="brand">SmartCampus</div>
        <div class="brand-sub">Facility Report</div>
        <?php if(isset($_SESSION['user'])): ?>
        <nav>
            <a href="index.php?page=dashboard">Dashboard</a>
            <a href="index.php?page=laporan">Data Laporan</a>
            <a href="index.php?page=tambah_laporan">Tambah Laporan</a>
            <a href="index.php?page=logout">Logout</a>
        </nav>
        <?php endif; ?>
    </aside>
    <main class="main-area">
        <header class="topbar">
            <div>
                <h1><?= $title ?? 'SmartCampus Facility' ?></h1>
                <p>Sistem Pelaporan Kerusakan Fasilitas Kampus</p>
            </div>
            <?php if(isset($_SESSION['user'])): ?>
            <div class="user-pill"><?= htmlspecialchars($_SESSION['user']['nama']) ?></div>
            <?php endif; ?>
        </header>
        <?php if(isset($_SESSION['error'])): ?><div class="alert error"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div><?php endif; ?>
        <?php if(isset($_SESSION['success'])): ?><div class="alert success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div><?php endif; ?>
