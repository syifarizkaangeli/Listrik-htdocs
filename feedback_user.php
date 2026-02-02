<?php
session_start();
include 'connect.php';

// Cek session
if (!isset($_SESSION['email'])) {
    header("Location: index.php"); // redirect ke login kalau belum login
    exit;
}

$email = $_SESSION['email'];
$success = '';
$error = '';

if (isset($_POST['submit'])) {
    $pesan = trim($_POST['pesan']);

    if (empty($pesan)) {
        $error = "Pesan tidak boleh kosong!";
    } else {
        // Insert ke tabel feedback (pastikan kolom waktu ada di DB)
        $stmt = $koneksi->prepare("INSERT INTO feedback (email, pesan, waktu) VALUES (?, ?, NOW())");
        $stmt->bind_param("ss", $email, $pesan);

        if ($stmt->execute()) {
            $success = "Terima kasih, pesan Anda berhasil dikirim!";
        } else {
            $error = "Terjadi kesalahan, silakan coba lagi.";
        }

        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Kritik & Saran</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<style>
body { background-color: #f8f9fa; }
</style>
</head>
<body>

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

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h5 class="mb-0">Kritik & Saran</h5>
                </div>
                <div class="card-body">
                    <?php if($success): ?>
                        <div class="alert alert-success"><?= $success; ?></div>
                    <?php endif; ?>
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?= $error; ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email" class="form-control" value="<?= $email; ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label>Pesan</label>
                            <textarea name="pesan" class="form-control" rows="5" placeholder="Ketik pesan Anda..." required></textarea>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary w-100">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
