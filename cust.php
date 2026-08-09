<?php
session_start();
include 'connect.php';

$showForm = false;
$editMode = false;
$rowEdit  = null;

/* ===== TAMBAH ===== */
if (isset($_GET['tambah'])) {
    $showForm = true;
}

/* ===== EDIT ===== */
if (isset($_GET['edit'])) {
    $showForm = true;
    $editMode = true;

    $idEdit = $_GET['edit'];
    $res = $koneksi->query("SELECT * FROM konsumen WHERE id_cust='$idEdit'");
    $rowEdit = $res->fetch_assoc();
}

/* ===== SIMPAN ===== */
if (isset($_POST['simpan'])) {
    $nama   = $_POST['nama'];
    $email  = $_POST['email'];
    $telp   = $_POST['telp'];
    $alamat = $_POST['alamat'];

    if (!empty($_POST['id_cust'])) {
        // UPDATE
        $id = $_POST['id_cust'];
        $koneksi->query("UPDATE konsumen SET
            nama='$nama',
            email='$email',
            telp='$telp',
            alamat='$alamat'
            WHERE id_cust='$id'
        ");
        $success = "Data berhasil diupdate!";
    } else {
        // INSERT
        $koneksi->query("INSERT INTO konsumen (nama,email,telp,alamat)
            VALUES ('$nama','$email','$telp','$alamat')
        ");
        $success = "Data berhasil ditambahkan!";
    }

    header("Location: cust.php");
    exit;
}

/* ===== HAPUS ===== */
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $koneksi->query("DELETE FROM konsumen WHERE id_cust='$id'");
    header("Location: cust.php");
    exit;
}

$result = $koneksi->query("SELECT * FROM konsumen");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Data Konsumen</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary position-relative">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="home.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link active" href="cust.php">Konsumen</a></li>
      <li class="nav-item"><a class="nav-link" href="tagihan.php">Tagihan</a></li>
      <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
    </ul>

    <div class="position-absolute end-0 me-3">
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container mt-5">

<?php if (isset($success)) { ?>
<div class="alert alert-success"><?= $success; ?></div>
<?php } ?>

<div class="d-flex justify-content-between mb-3">
    <h4>Data Konsumen</h4>
    <!-- TOMBOL TAMBAH -->
    <a href="?tambah=1" class="btn btn-success">Tambah Konsumen</a>
</div>

<!-- FORM -->
<?php if ($showForm) { ?>
<div class="card shadow col-md-6 mx-auto mb-4">
    <div class="card-header <?= $editMode ? 'bg-primary' : 'bg-success'; ?> text-white">
        <?= $editMode ? 'Edit Konsumen' : 'Tambah Konsumen'; ?>
    </div>
    <div class="card-body">
        <form method="POST">

            <?php if ($editMode) { ?>
                <input type="hidden" name="id_cust" value="<?= $rowEdit['id_cust']; ?>">
            <?php } ?>

            <div class="mb-3">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control"
                       value="<?= $editMode ? $rowEdit['nama'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control"
                       value="<?= $editMode ? $rowEdit['email'] : ''; ?>" required>
            </div>

            <div class="mb-3">
                <label>No. Telp</label>
                <input type="text" name="telp" class="form-control"
                       value="<?= $editMode ? $rowEdit['telp'] : ''; ?>">
            </div>

            <div class="mb-3">
                <label>Alamat</label>
                <textarea name="alamat" class="form-control"><?= $editMode ? $rowEdit['alamat'] : ''; ?></textarea>
            </div>

            <button type="submit" name="simpan" class="btn btn-success">
                <?= $editMode ? 'Update' : 'Simpan'; ?>
            </button>
            <a href="cust.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php } ?>

<!-- TABLE -->
<table class="table table-bordered table-striped">
<thead class="table-dark text-center">
<tr>
<th>ID</th>
<th>Nama</th>
<th>Email</th>
<th>Telp</th>
<th>Alamat</th>
<th>Aksi</th>
</tr>
</thead>
<tbody>
<?php while ($row = $result->fetch_assoc()) { ?>
<tr>
<td class="text-center"><?= $row['id_cust']; ?></td>
<td><?= $row['nama']; ?></td>
<td><?= $row['email']; ?></td>
<td><?= $row['telp']; ?></td>
<td><?= $row['alamat']; ?></td>
<td class="text-center">
    <a href="?edit=<?= $row['id_cust']; ?>" class="btn btn-primary btn-sm">Edit</a>
    <a href="?hapus=<?= $row['id_cust']; ?>"
       onclick="return confirm('Hapus data?')"
       class="btn btn-danger btn-sm">Hapus</a>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>
<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

</body>
</html>
