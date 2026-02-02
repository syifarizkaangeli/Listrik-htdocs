<?php
session_start();
include 'connect.php';

// Ambil total konsumen
$q = $koneksi->query("SELECT COUNT(*) AS total FROM konsumen");
$data = $q->fetch_assoc();

// Ambil jumlah konsumen yang belum lunas
// Mengambil dari tabel tagihan, hanya yang pembayaran = 'Belum Lunas'
// DISTINCT untuk memastikan satu konsumen dihitung sekali
$q_blm = $koneksi->query("
    SELECT COUNT(DISTINCT TRIM(LOWER(k.email))) AS total_blm
    FROM tagihan t
    JOIN konsumen k ON t.email = k.email
    WHERE t.pembayaran = 'Belum Lunas'
");
$data_blm = $q_blm->fetch_assoc();

// Harga per kWh
$hargaPerKwh = 1000;
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Beranda</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link active" href="home.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link" href="cust.php">Konsumen</a></li>
      <li class="nav-item"><a class="nav-link" href="tagihan.php">Tagihan</a></li>
      <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
    </ul>

    <div class="position-absolute end-0 me-3">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- CONTAINER -->
<div class="container mt-5">
  <div class="row justify-content-center g-4">

    <!-- CARD 1: Jumlah Konsumen -->
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Jumlah Konsumen</h5>
          <h1 class="text-primary"><?= $data['total']; ?></h1>
          <p class="text-muted">Konsumen terdaftar</p>
        </div>
      </div>
    </div>

    <!-- CARD 2: Harga per kWh -->
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Harga per kWh</h5>
          <h1 class="text-success">Rp <?= number_format($hargaPerKwh, 0, ',', '.'); ?></h1>
          <p class="text-muted">Harga listrik per kWh</p>
        </div>
      </div>
    </div>

    <!-- CARD 3: Belum Lunas -->
    <div class="col-md-4">
      <div class="card text-center shadow">
        <div class="card-body">
          <h5 class="card-title">Belum Lunas</h5>
          <h1 class="text-danger"><?= $data_blm['total_blm']; ?></h1>
          <p class="text-muted">Konsumen yang belum membayar</p>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- FOOTER -->
<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
