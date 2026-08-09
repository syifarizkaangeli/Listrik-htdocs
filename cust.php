<?php

session_start();

include 'connect.php';

/*
|--------------------------------------------------------------------------
| Cek Login Admin
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Variabel
|--------------------------------------------------------------------------
*/

$editMode = false;
$rowEdit = null;
$error = '';
$success = '';

/*
|--------------------------------------------------------------------------
| Hapus Data Konsumen
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus'])) {

    $id_cust = (int) $_GET['hapus'];

    if ($id_cust > 0) {

        $stmt = $koneksi->prepare(
            "DELETE FROM konsumen WHERE id_cust = ?"
        );

        if ($stmt) {

            $stmt->bind_param("i", $id_cust);

            if ($stmt->execute()) {
                header("Location: cust.php?status=deleted");
                exit;
            } else {
                $error = "Data konsumen gagal dihapus.";
            }

            $stmt->close();

        } else {
            $error = "Query database gagal.";
        }
    }
}


/*
|--------------------------------------------------------------------------
| Ambil Data Untuk Edit
|--------------------------------------------------------------------------
*/

if (isset($_GET['edit'])) {

    $id_cust = (int) $_GET['edit'];

    if ($id_cust > 0) {

        $stmt = $koneksi->prepare(
            "SELECT id_cust, nama, email, telp, alamat
             FROM konsumen
             WHERE id_cust = ?
             LIMIT 1"
        );

        if ($stmt) {

            $stmt->bind_param("i", $id_cust);

            $stmt->execute();

            $resultEdit = $stmt->get_result();

            if ($resultEdit && $resultEdit->num_rows === 1) {

                $rowEdit = $resultEdit->fetch_assoc();

                $editMode = true;

            } else {

                $error = "Data konsumen tidak ditemukan.";

            }

            $stmt->close();

        } else {

            $error = "Query database gagal.";

        }
    }
}


/*
|--------------------------------------------------------------------------
| Simpan / Update Data
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['simpan'])) {

    $nama = trim($_POST['nama'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telp = trim($_POST['telp'] ?? '');
    $alamat = trim($_POST['alamat'] ?? '');

    $id_cust = isset($_POST['id_cust'])
        ? (int) $_POST['id_cust']
        : 0;


    /*
    | Validasi
    */

    if ($nama === '') {

        $error = "Nama wajib diisi.";

    } elseif ($email === '') {

        $error = "Email wajib diisi.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Format email tidak valid.";

    } else {


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        if ($id_cust > 0) {

            $stmt = $koneksi->prepare(
                "UPDATE konsumen
                 SET nama = ?, email = ?, telp = ?, alamat = ?
                 WHERE id_cust = ?"
            );

            if ($stmt) {

                $stmt->bind_param(
                    "ssssi",
                    $nama,
                    $email,
                    $telp,
                    $alamat,
                    $id_cust
                );

                if ($stmt->execute()) {

                    header("Location: cust.php?status=updated");
                    exit;

                } else {

                    $error = "Data konsumen gagal diperbarui.";

                }

                $stmt->close();

            } else {

                $error = "Query update gagal.";

            }


        /*
        |--------------------------------------------------------------------------
        | INSERT
        |--------------------------------------------------------------------------
        */

        } else {

            $stmt = $koneksi->prepare(
                "INSERT INTO konsumen
                 (nama, email, telp, alamat)
                 VALUES (?, ?, ?, ?)"
            );

            if ($stmt) {

                $stmt->bind_param(
                    "ssss",
                    $nama,
                    $email,
                    $telp,
                    $alamat
                );

                if ($stmt->execute()) {

                    header("Location: cust.php?status=success");
                    exit;

                } else {

                    /*
                    | Jika email dibuat UNIQUE di database,
                    | error duplicate akan ditangani di sini.
                    */

                    if ($koneksi->errno === 1062) {

                        $error =
                            "Email tersebut sudah terdaftar.";

                    } else {

                        $error =
                            "Data konsumen gagal disimpan.";

                    }
                }

                $stmt->close();

            } else {

                $error = "Query insert gagal.";

            }
        }
    }
}


/*
|--------------------------------------------------------------------------
| Status Message
|--------------------------------------------------------------------------
*/

if (isset($_GET['status'])) {

    if ($_GET['status'] === 'success') {

        $success = "Data konsumen berhasil ditambahkan.";

    } elseif ($_GET['status'] === 'updated') {

        $success = "Data konsumen berhasil diperbarui.";

    } elseif ($_GET['status'] === 'deleted') {

        $success = "Data konsumen berhasil dihapus.";

    }
}


/*
|--------------------------------------------------------------------------
| Ambil Semua Data Konsumen
|--------------------------------------------------------------------------
*/

$result = $koneksi->query(
    "SELECT
        id_cust,
        nama,
        email,
        telp,
        alamat
     FROM konsumen
     ORDER BY id_cust DESC"
);

?>

<!DOCTYPE html>

<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Data Konsumen - Aplikasi Tagihan Listrik
    </title>

    <!-- Bootstrap -->
    <link
        rel="stylesheet"
        href="bootstrap/css/bootstrap.min.css"
    >

    <!-- Custom CSS -->
    <link
        rel="stylesheet"
        href="style.css"
    >

</head>


<body>


<!-- =====================================================
     NAVBAR
     ===================================================== -->

<nav class="navbar navbar-expand-lg navbar-dark">

    <div class="container position-relative">

        <a
            class="navbar-brand"
            href="home.php"
        >
            ⚡ Tagihan Listrik
        </a>


        <!-- Mobile Toggle -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
            aria-controls="navbarMenu"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="home.php"
                    >
                        Beranda
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="cust.php"
                    >
                        Konsumen
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="tagihan.php"
                    >
                        Tagihan
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="feedback.php"
                    >
                        Feedback
                    </a>

                </li>

            </ul>


            <!-- Logout -->

            <div class="d-flex">

                <a
                    href="logout.php"
                    class="btn btn-outline-light btn-sm"
                >
                    Logout
                </a>

            </div>

        </div>

    </div>

</nav>


<!-- =====================================================
     MAIN CONTENT
     ===================================================== -->

<main>

    <div class="container mt-5">


        <!-- =================================================
             PAGE TITLE
             ================================================= -->

        <div class="mb-4">

            <h3 class="mb-1">
                Data Konsumen
            </h3>

            <p class="text-muted mb-0">
                Kelola data pelanggan listrik.
            </p>

        </div>


        <!-- =================================================
             ALERT SUCCESS
             ================================================= -->

        <?php if ($success !== '') : ?>

            <div
                class="alert alert-success"
                role="alert"
            >

                <?= htmlspecialchars($success); ?>

            </div>

        <?php endif; ?>


        <!-- =================================================
             ALERT ERROR
             ================================================= -->

        <?php if ($error !== '') : ?>

            <div
                class="alert alert-danger"
                role="alert"
            >

                <?= htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <div class="row g-4">


            <!-- =================================================
                 FORM KONSUMEN
                 ================================================= -->

            <div class="col-lg-4">

                <div class="card">

                    <div class="card-header">

                        <?= $editMode
                            ? 'Edit Konsumen'
                            : 'Tambah Konsumen'; ?>

                    </div>


                    <div class="card-body">

                        <form
                            method="POST"
                            action="cust.php<?= $editMode
                                ? '?edit=' . $rowEdit['id_cust']
                                : ''; ?>"
                        >


                            <?php if ($editMode) : ?>

                                <input
                                    type="hidden"
                                    name="id_cust"
                                    value="<?= htmlspecialchars(
                                        $rowEdit['id_cust']
                                    ); ?>"
                                >

                            <?php endif; ?>


                            <!-- Nama -->

                            <div class="mb-3">

                                <label
                                    for="nama"
                                    class="form-label"
                                >
                                    Nama
                                </label>

                                <input
                                    type="text"
                                    id="nama"
                                    name="nama"
                                    class="form-control"
                                    placeholder="Masukkan nama konsumen"
                                    value="<?= $editMode
                                        ? htmlspecialchars($rowEdit['nama'])
                                        : ''; ?>"
                                    required
                                >

                            </div>


                            <!-- Email -->

                            <div class="mb-3">

                                <label
                                    for="email"
                                    class="form-label"
                                >
                                    Email
                                </label>

                                <input
                                    type="email"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="contoh@email.com"
                                    value="<?= $editMode
                                        ? htmlspecialchars($rowEdit['email'])
                                        : ''; ?>"
                                    required
                                >

                            </div>


                            <!-- Telepon -->

                            <div class="mb-3">

                                <label
                                    for="telp"
                                    class="form-label"
                                >
                                    No. Telp
                                </label>

                                <input
                                    type="text"
                                    id="telp"
                                    name="telp"
                                    class="form-control"
                                    placeholder="08xxxxxxxxxx"
                                    value="<?= $editMode
                                        ? htmlspecialchars($rowEdit['telp'])
                                        : ''; ?>"
                                >

                            </div>


                            <!-- Alamat -->

                            <div class="mb-3">

                                <label
                                    for="alamat"
                                    class="form-label"
                                >
                                    Alamat
                                </label>

                                <textarea
                                    id="alamat"
                                    name="alamat"
                                    class="form-control"
                                    placeholder="Masukkan alamat konsumen"
                                    rows="4"
                                ><?= $editMode
                                    ? htmlspecialchars($rowEdit['alamat'])
                                    : ''; ?></textarea>

                            </div>


                            <!-- Button -->

                            <div class="d-flex gap-2">

                                <button
                                    type="submit"
                                    name="simpan"
                                    class="btn btn-success"
                                >

                                    <?= $editMode
                                        ? 'Update'
                                        : 'Simpan'; ?>

                                </button>


                                <?php if ($editMode) : ?>

                                    <a
                                        href="cust.php"
                                        class="btn btn-secondary"
                                    >
                                        Batal
                                    </a>

                                <?php endif; ?>

                            </div>

                        </form>

                    </div>

                </div>

            </div>


            <!-- =================================================
                 DATA TABLE
                 ================================================= -->

            <div class="col-lg-8">

                <div class="card">

                    <div class="card-header d-flex justify-content-between align-items-center">

                        <span>
                            Daftar Konsumen
                        </span>

                        <span class="badge bg-primary">

                            <?php
                            echo $result
                                ? $result->num_rows
                                : 0;
                            ?>

                            Konsumen

                        </span>

                    </div>


                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-hover mb-0">

                                <thead>

                                    <tr>

                                        <th>
                                            ID
                                        </th>

                                        <th>
                                            Nama
                                        </th>

                                        <th>
                                            Email
                                        </th>

                                        <th>
                                            No. Telp
                                        </th>

                                        <th>
                                            Alamat
                                        </th>

                                        <th>
                                            Aksi
                                        </th>

                                    </tr>

                                </thead>


                                <tbody>

                                    <?php if (
                                        $result &&
                                        $result->num_rows > 0
                                    ) : ?>


                                        <?php while (
                                            $row =
                                            $result->fetch_assoc()
                                        ) : ?>

                                            <tr>

                                                <td>
                                                    <?= htmlspecialchars(
                                                        $row['id_cust']
                                                    ); ?>
                                                </td>


                                                <td>

                                                    <strong>
                                                        <?= htmlspecialchars(
                                                            $row['nama']
                                                        ); ?>
                                                    </strong>

                                                </td>


                                                <td>

                                                    <?= htmlspecialchars(
                                                        $row['email']
                                                    ); ?>

                                                </td>


                                                <td>

                                                    <?= htmlspecialchars(
                                                        $row['telp'] ?? '-'
                                                    ); ?>

                                                </td>


                                                <td>

                                                    <?= htmlspecialchars(
                                                        $row['alamat'] ?? '-'
                                                    ); ?>

                                                </td>


                                                <td>

                                                    <div class="d-flex gap-1">

                                                        <a
                                                            href="cust.php?edit=<?= (int) $row['id_cust']; ?>"
                                                            class="btn btn-primary btn-sm"
                                                        >
                                                            Edit
                                                        </a>


                                                        <a
                                                            href="cust.php?hapus=<?= (int) $row['id_cust']; ?>"
                                                            class="btn btn-danger btn-sm"
                                                            onclick="return confirm('Yakin ingin menghapus konsumen ini?');"
                                                        >
                                                            Hapus
                                                        </a>

                                                    </div>

                                                </td>

                                            </tr>

                                        <?php endwhile; ?>


                                    <?php else : ?>

                                        <tr>

                                            <td
                                                colspan="6"
                                                class="text-center py-4"
                                            >

                                                <div class="text-muted">

                                                    Belum ada data konsumen.

                                                </div>

                                            </td>

                                        </tr>

                                    <?php endif; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</main>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer>

    <small>
        © <?= date('Y'); ?> Aplikasi Tagihan Listrik
    </small>

</footer>


<!-- Bootstrap JS -->

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>