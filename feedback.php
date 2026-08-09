<?php
session_start();
include 'connect.php';

/* QUERY JOIN FEEDBACK + KONSUMEN */
$result = $koneksi->query("
    SELECT feedback.id, feedback . email, feedback . pesan
    FROM feedback
    JOIN konsumen ON feedback . email = konsumen . email
");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Feedback Pelanggan</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary position-relative">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="home.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link" href="cust.php">Konsumen</a></li>
      <li class="nav-item"><a class="nav-link" href="tagihan.php">Tagihan</a></li>
      <li class="nav-item"><a class="nav-link active" href="feedback.php">Feedback</a></li>
    </ul>

    <div class="position-absolute end-0 me-3">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">
    <h4 class="mb-3">Feedback Pelanggan</h4>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>ID</th>
                    <th>Nama Konsumen</th>
                    <th>Feedback</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($result->num_rows > 0) { ?>
                <?php while($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td class="text-center"><?= $row['id']; ?></td>
                    <td><?= $row['nama']; ?></td>
                    <td><?= $row['feedback']; ?></td>
                </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        Belum ada feedback
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
