<?php
session_start();
include 'connect.php';

// Cek session
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // redirect ke login user
    exit;
}

$email = $_SESSION['email'];

// Ambil tagihan user
$result = $koneksi->query("SELECT * FROM tagihan WHERE email='$email' ORDER BY id_tagih DESC");

// Tanggal hari ini
$today = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tagihan Saya</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<style>
.lunas { color: white; font-weight: bold; }
.overdue { background-color: #f8d7da !important; } /* merah untuk tagihan lebih dari sebulan */
</style>
</head>
<body class="bg-light">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="home_user.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link active" href="tagihan_user.php">Tagihan</a></li>
      <li class="nav-item"><a class="nav-link" href="me.php">Saya</a></li>
    </ul>
    <div class="position-absolute end-0 me-3">
      <a href="logout_user.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h4 class="mb-3">Tagihan Saya</h4>

    <?php if($result->num_rows > 0): ?>
    <table class="table table-striped table-bordered">
        <thead class="table-dark text-center">
            <tr>
                <th>No</th> <!-- ID urut -->
                <th>Jumlah Pakai (kWh)</th>
                <th>Periode</th>
                <th>Harga</th>
                <th>Deadline</th>
                <th>Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $no = 1; // counter untuk ID urut
            while($row = $result->fetch_assoc()): 
                $isOverdue = false;

                // Hanya yang Belum Lunas bisa overdue
                if($row['pembayaran'] == 'Belum Lunas') {
                    $deadline = $row['deadline'];
                    if($deadline && strtotime($deadline)) {
                        $diff = (strtotime($today) - strtotime($deadline)) / (60*60*24); // selisih hari
                        if($diff > 30){
                            $isOverdue = true;
                        }
                    }
                }
            ?>
            <tr class="text-center <?= $isOverdue ? 'overdue' : ''; ?>">
                <td><?= $no++; ?></td> <!-- tampilkan counter -->
                <td><?= $row['jumlah_pakai']; ?></td>
                <td><?= $row['periode']; ?></td>
                <td>Rp <?= number_format($row['harga'],0,',','.'); ?></td>
                <td><?= $row['deadline']; ?></td>
                <td>
                    <?php if($row['pembayaran']=='Belum Lunas'): ?>
                        <?php if($isOverdue): ?>
                            <span class="badge bg-danger text-white">Terlambat</span>
                        <?php else: ?>
                            <span class="badge bg-warning text-dark">Belum Lunas</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge bg-success lunas">Lunas</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <?php else: ?>
        <div class="alert alert-info">Belum ada tagihan untuk Anda.</div>
    <?php endif; ?>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
