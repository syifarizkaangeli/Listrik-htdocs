<?php
session_start();
include 'connect.php';

// ========================
// CEK SESSION
// ========================
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit;
}

// Ambil email user, normalisasi
$email = strtolower(trim($_SESSION['email']));

// ========================
// AMBIL TOTAL KONSUMEN
// ========================
$q = $koneksi->query("SELECT COUNT(*) AS total FROM konsumen");
$data = $q->fetch_assoc();

// ========================
// AMBIL TOTAL TAGIHAN BELUM LUNAS USER
// ========================
// SUM harga semua tagihan user yang Belum Lunas
$qTagihan = $koneksi->query("
    SELECT SUM(harga) AS total_tagihan
    FROM tagihan
    WHERE LOWER(TRIM(email)) = '$email'
      AND pembayaran = 'Belum Lunas'
");
$tagihan = $qTagihan->fetch_assoc();
$totalTagihan = $tagihan['total_tagihan'] ?? 0;

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Beranda User</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<style>
.card-hover{
    transition: 0.3s;
}
.card-hover:hover{
    transform: scale(1.05);
    box-shadow: 0 8px 20px rgba(0,0,0,0.2);
}
.card-body-centered {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    height: 100%;
}
</style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link active" href="home_user.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link" href="tagihan_user.php">Tagihan</a></li>
      <li class="nav-item"><a class="nav-link" href="me.php">Saya</a></li>
    </ul>
    <div class="position-absolute end-0 me-3">
      <a href="logout_user.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- CONTAINER -->
<div class="container mt-5">
  <div class="row justify-content-center g-4">

    <!-- CARD 1: Kritik & Saran -->
    <div class="col-md-4">
      <a href="feedback_user.php" class="text-decoration-none text-dark">
        <div class="card shadow card-hover h-100 border-primary">
          <div class="card-body card-body-centered text-center">
            <h5 class="card-title">Isi Kritik & Saran</h5>
          </div>
        </div>
      </a>
    </div>

    <!-- CARD 2: Tagihan Belum Dibayar -->
    <div class="col-md-4">
      <a href="tagihan_user.php" class="text-decoration-none text-dark">
        <div class="card shadow card-hover h-100 border-danger">
          <div class="card-body card-body-centered text-center">
            <h5 class="card-title text-danger">Tagihan Belum Dibayar</h5>
            <h1 class="text-danger">Rp <?= number_format($totalTagihan,0,',','.'); ?></h1>
            <br>
            <?php if($totalTagihan > 0): ?>
                <span class="badge bg-danger">Segera Bayar</span>
            <?php else: ?>
                <span class="badge bg-success">Aman</span>
            <?php endif; ?>
          </div>
        </div>
      </a>
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
