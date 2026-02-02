<?php
session_start();
include 'connect.php';

/* ===============================
   CEK LOGIN
================================ */
if (!isset($_SESSION['email'])) {
    header("Location: me.php");
    exit;
}

$email = $_SESSION['email'];

/* ===============================
   AMBIL DATA USER
================================ */
$qUser = $koneksi->query("
    SELECT *
    FROM konsumen
    WHERE email = '$email'
    LIMIT 1
");

$user = $qUser->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Akun Saya</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
</head>

<body class="bg-light">

<!-- ================= NAVBAR ================= -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">

    <ul class="navbar-nav mx-auto">
      <li class="nav-item">
        <a class="nav-link" href="home_user.php">Beranda</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="tagihan.php">Tagihan</a>
      </li>
      <li class="nav-item">
        <a class="nav-link active" href="me.php">Saya</a>
      </li>
    </ul>

    <div class="position-absolute end-0 me-3">
      <a href="logout_user.php" class="btn btn-outline-light btn-sm">
        Logout
      </a>
    </div>

  </div>
</nav>

          <table class="table table-borderless">
            <tr>
              <th width="40%">Nama</th>
              <td>: <?= htmlspecialchars($user['nama']); ?></td>
            </tr>
            <tr>
              <th>Email</th>
              <td>: <?= htmlspecialchars($user['email']); ?></td>
            </tr>
            <tr>
              <th>No. Telp</th>
              <td>: <?= htmlspecialchars($user['telp']); ?></td>
            </tr>
            <tr>
              <th>Alamat</th>
              <td>: <?= htmlspecialchars($user['alamat']); ?></td>
            </tr>
          </table>

        </div>

        <div class="card-footer text-center">
          <a href="home_user.php" class="btn btn-secondary btn-sm">
            Kembali
          </a>
        </div>

      </div>

    </div>

  </div>
</div>

<!-- ================= FOOTER ================= -->
<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
