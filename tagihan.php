<?php
include 'connect.php';

/* ===== SIMPAN ===== */
if (isset($_POST['simpan'])) {
    $email        = $_POST['email'];
    $jumlah_pakai = $_POST['jumlah_pakai'];
    $periode      = $_POST['periode'];
    $harga        = $_POST['harga'];
    $deadline     = date('Y-m-d', strtotime('+1 month', strtotime($periode)));

    $koneksi->query("INSERT INTO tagihan (email,jumlah_pakai,periode,harga,deadline,pembayaran)
        VALUES ('$email','$jumlah_pakai','$periode','$harga','$deadline','Belum Lunas')
    ");

    header("Location: tagihan.php");
    exit;
}

/* ===== UPDATE STATUS LUNAS via AJAX ===== */
if (isset($_GET['lunas'])) {
    $id = $_GET['lunas'];
    $koneksi->query("UPDATE tagihan SET pembayaran='Lunas' WHERE id_tagih='$id'");
    echo 'success';
    exit;
}

$result = $koneksi->query("SELECT t.*, k.nama FROM tagihan t JOIN konsumen k ON t.email=k.email ORDER BY t.id_tagih DESC");
$konsumen = $koneksi->query("SELECT email,nama FROM konsumen");
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Tagihan Konsumen</title>
<link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="style.css">
<style>
.lunas { color: white; font-weight: bold; }
</style>
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container">
    <ul class="navbar-nav mx-auto">
      <li class="nav-item"><a class="nav-link" href="home.php">Beranda</a></li>
      <li class="nav-item"><a class="nav-link" href="cust.php">Konsumen</a></li>
      <li class="nav-item"><a class="nav-link active" href="tagihan.php">Tagihan</a></li>
      <li class="nav-item"><a class="nav-link" href="feedback.php">Feedback</a></li>
    </ul>

    <div class="position-absolute end-0 me-3">
      <a href="logout.php" class="btn btn-outline-light btn-sm">
        Logout
      </a>
    </div>
  </div>
</nav>

<div class="container mt-5">

<div class="d-flex justify-content-between mb-3">
    <h4>Daftar Tagihan</h4>
    <a href="?tambah=1" class="btn btn-success">Tambah Tagihan</a>
</div>

<!-- FORM TAMBAH -->
<?php if (isset($_GET['tambah'])) { ?>
<div class="card shadow mb-4">
    <div class="card-header bg-success text-white">
        Tambah Tagihan
    </div>
    <div class="card-body">
        <form method="POST">
            <div class="mb-3">
                <label>Nama Konsumen</label>
                <select name="email" class="form-select" required>
                    <?php while($k = $konsumen->fetch_assoc()) { 
                        echo "<option value='{$k['email']}'>{$k['nama']}</option>";
                    } ?>
                </select>
            </div>

            <div class="mb-3">
                <label>Jumlah Pakai (kwh)</label>
                <input type="number" name="jumlah_pakai" id="jumlah_pakai" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Periode</label>
                <input type="date" name="periode" id="periode" class="form-control" required>
            </div>

            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="harga" id="harga" class="form-control" readonly>
            </div>

            <button type="submit" name="simpan" class="btn btn-success">Simpan</button>
            <a href="tagihan.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
<?php } ?>

<!-- TABEL TAGIHAN -->
<table class="table table-striped table-bordered">
<thead class="table-dark text-center">
<tr>
<th>ID</th>
<th>Nama</th>
<th>Jumlah Pakai</th>
<th>Periode</th>
<th>Harga</th>
<th>Deadline</th>
<th>Pembayaran</th>
</tr>
</thead>
<tbody>
<?php while($row = $result->fetch_assoc()) { ?>
<tr>
<td class="text-center"><?= $row['id_tagih']; ?></td>
<td><?= $row['nama']; ?></td>
<td><?= $row['jumlah_pakai']; ?></td>
<td><?= $row['periode']; ?></td>
<td><?= $row['harga']; ?></td>
<td><?= $row['deadline']; ?></td>
<td class="text-center">
    <?php if($row['pembayaran']=='Belum Lunas') { ?>
        <button class="btn btn-warning btn-sm btn-lunas" data-id="<?= $row['id_tagih']; ?>">Belum Lunas</button>
    <?php } else { ?>
        <span class="badge bg-success lunas">Lunas</span>
    <?php } ?>
</td>
</tr>
<?php } ?>
</tbody>
</table>

</div>

<footer class="bg-primary text-white text-center py-3 mt-5 fixed-bottom">
  <small>© 2026 Aplikasi Tagihan Listrik</small>
</footer>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
// Hitung harga otomatis
const jumlahInput = document.getElementById('jumlah_pakai');
const hargaInput = document.getElementById('harga');
const periodeInput = document.getElementById('periode');

if(jumlahInput && hargaInput){
    jumlahInput.addEventListener('input', updateHarga);
    if(periodeInput) periodeInput.addEventListener('change', updateHarga);

    function updateHarga(){
        const jumlah = parseInt(jumlahInput.value) || 0;
        hargaInput.value = jumlah * 100;
    }
}

// Tombol Belum Lunas → Lunas
document.querySelectorAll('.btn-lunas').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const id = btn.dataset.id;
        fetch('tagihan.php?lunas='+id)
        .then(res=>res.text())
        .then(res=>{
            if(res=='success'){
                btn.outerHTML = '<span class="badge bg-success lunas">Lunas</span>';
            }
        });
    });
});
</script>

</body>
</html>
