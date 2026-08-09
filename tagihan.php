<?php

session_start();

include 'connect.php';

/*
|--------------------------------------------------------------------------
| CEK LOGIN ADMIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| HARGA LISTRIK PER KWH
|--------------------------------------------------------------------------
|
| Ubah angka ini jika tarif listrik ingin diganti.
|
*/

$hargaPerKwh = 1250;


/*
|--------------------------------------------------------------------------
| PROSES SIMPAN TAGIHAN
|--------------------------------------------------------------------------
*/

if (isset($_POST['simpan'])) {

    $email = trim($_POST['email'] ?? '');
    $jumlah_pakai = (int) ($_POST['jumlah_pakai'] ?? 0);
    $periode = trim($_POST['periode'] ?? '');
    $deadline = $_POST['deadline'] ?? '';
    $pembayaran = $_POST['pembayaran'] ?? 'Belum Lunas';


    /*
    |--------------------------------------------------------------------------
    | VALIDASI
    |--------------------------------------------------------------------------
    */

    if (
        empty($email) ||
        $jumlah_pakai <= 0 ||
        empty($periode) ||
        empty($deadline)
    ) {

        echo "<script>
                alert('Semua data tagihan wajib diisi.');
                window.location='tagihan.php';
              </script>";

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | HITUNG HARGA OTOMATIS
    |--------------------------------------------------------------------------
    */

    $harga = $jumlah_pakai * $hargaPerKwh;


    /*
    |--------------------------------------------------------------------------
    | CEK EMAIL KONSUMEN
    |--------------------------------------------------------------------------
    */

    $cek = $koneksi->prepare("
        SELECT email
        FROM konsumen
        WHERE email = ?
        LIMIT 1
    ");

    $cek->bind_param("s", $email);

    $cek->execute();

    $hasilCek = $cek->get_result();

    if ($hasilCek->num_rows === 0) {

        $cek->close();

        echo "<script>
                alert('Konsumen tidak ditemukan.');
                window.location='tagihan.php';
              </script>";

        exit;
    }

    $cek->close();


    /*
    |--------------------------------------------------------------------------
    | SIMPAN KE DATABASE
    |--------------------------------------------------------------------------
    */

    $stmt = $koneksi->prepare("
        INSERT INTO tagihan
        (
            email,
            jumlah_pakai,
            periode,
            harga,
            deadline,
            pembayaran
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {

        die(
            "Query simpan tagihan gagal: "
            . $koneksi->error
        );

    }


    $stmt->bind_param(
        "sis dss",
        $email,
        $jumlah_pakai,
        $periode,
        $harga,
        $deadline,
        $pembayaran
    );

    /*
    |--------------------------------------------------------------------------
    | Perbaikan format bind_param
    |--------------------------------------------------------------------------
    */

    $stmt->close();


    $stmt = $koneksi->prepare("
        INSERT INTO tagihan
        (
            email,
            jumlah_pakai,
            periode,
            harga,
            deadline,
            pembayaran
        )
        VALUES (?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sisdss",
        $email,
        $jumlah_pakai,
        $periode,
        $harga,
        $deadline,
        $pembayaran
    );


    if ($stmt->execute()) {

        $stmt->close();

        echo "<script>
                alert('Tagihan berhasil ditambahkan.');
                window.location='tagihan.php';
              </script>";

        exit;

    } else {

        $error = $stmt->error;

        $stmt->close();

        echo "<script>
                alert('Gagal menyimpan tagihan: "
                . htmlspecialchars($error, ENT_QUOTES)
                . "');
                window.location='tagihan.php';
              </script>";

        exit;
    }
}


/*
|--------------------------------------------------------------------------
| HAPUS TAGIHAN
|--------------------------------------------------------------------------
*/

if (isset($_GET['hapus'])) {

    $id_tagih = (int) $_GET['hapus'];

    if ($id_tagih > 0) {

        $stmt = $koneksi->prepare("
            DELETE FROM tagihan
            WHERE id_tagih = ?
        ");

        $stmt->bind_param(
            "i",
            $id_tagih
        );

        $stmt->execute();

        $stmt->close();
    }

    header("Location: tagihan.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| UPDATE STATUS PEMBAYARAN
|--------------------------------------------------------------------------
*/

if (
    isset($_POST['update_status']) &&
    isset($_POST['id_tagih'])
) {

    $id_tagih = (int) $_POST['id_tagih'];

    $pembayaran =
        $_POST['pembayaran'] ?? 'Belum Lunas';


    if (
        $pembayaran !== 'Lunas' &&
        $pembayaran !== 'Belum Lunas'
    ) {

        $pembayaran = 'Belum Lunas';

    }


    $stmt = $koneksi->prepare("
        UPDATE tagihan
        SET pembayaran = ?
        WHERE id_tagih = ?
    ");

    $stmt->bind_param(
        "si",
        $pembayaran,
        $id_tagih
    );

    $stmt->execute();

    $stmt->close();


    header("Location: tagihan.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| AMBIL DATA KONSUMEN
|--------------------------------------------------------------------------
*/

$konsumen = $koneksi->query("
    SELECT
        id_cust,
        nama,
        email
    FROM konsumen
    ORDER BY nama ASC
");


/*
|--------------------------------------------------------------------------
| AMBIL SEMUA TAGIHAN
|--------------------------------------------------------------------------
*/

$result = $koneksi->query("
    SELECT
        t.id_tagih,
        t.email,
        t.jumlah_pakai,
        t.periode,
        t.harga,
        t.deadline,
        t.pembayaran,
        k.nama
    FROM tagihan t
    LEFT JOIN konsumen k
        ON t.email = k.email
    ORDER BY
        t.deadline DESC,
        t.id_tagih DESC
");


if (!$result) {

    die(
        "Query tagihan gagal: "
        . $koneksi->error
    );

}


$today = date('Y-m-d');

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
        Kelola Tagihan - Admin
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


    <style>

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            margin-bottom: 5px;
        }

        .page-header p {
            color: #64748b;
            margin-bottom: 0;
        }

        .tagihan-form-card {
            border-radius: 16px;
            border: none;
        }

        .tagihan-table-card {
            border-radius: 16px;
            border: none;
            overflow: hidden;
        }

        .overdue {
            background-color: #fff5f5 !important;
        }

        .status-form {
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .status-form select {
            min-width: 125px;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
            justify-content: center;
            flex-wrap: wrap;
        }

        @media (max-width: 768px) {

            .status-form {
                flex-direction: column;
                width: 100%;
            }

            .status-form select {
                width: 100%;
            }

            .action-buttons {
                flex-direction: column;
            }

            .action-buttons .btn {
                width: 100%;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
     ===================================================== -->

<nav class="navbar navbar-expand-lg navbar-dark">

    <div class="container">

        <a
            class="navbar-brand"
            href="home.php"
        >
            ⚡ Admin Listrik
        </a>


        <!-- MOBILE TOGGLE -->

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


        <!-- NAVIGATION -->

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
                        class="nav-link"
                        href="cust.php"
                    >
                        Konsumen
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
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


            <!-- LOGOUT -->

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
     MAIN
     ===================================================== -->

<main>

    <div class="container mt-5 mb-5">


        <!-- PAGE HEADER -->

        <div class="page-header">

            <h2>
                Kelola Tagihan
            </h2>

            <p>
                Tambahkan dan kelola tagihan listrik konsumen.
            </p>

        </div>


        <!-- =================================================
             FORM TAMBAH TAGIHAN
             ================================================= -->

        <div class="card shadow tagihan-form-card mb-4">

            <div class="card-header bg-primary text-white">

                <strong>
                    Tambah Tagihan
                </strong>

            </div>


            <div class="card-body">

                <form
                    method="POST"
                    action="tagihan.php"
                >


                    <!-- KONSUMEN -->

                    <div class="mb-3">

                        <label for="email">
                            Konsumen
                        </label>

                        <select
                            name="email"
                            id="email"
                            class="form-select"
                            required
                        >

                            <option value="">
                                -- Pilih Konsumen --
                            </option>


                            <?php if ($konsumen && $konsumen->num_rows > 0): ?>

                                <?php while (
                                    $k = $konsumen->fetch_assoc()
                                ): ?>

                                    <option
                                        value="<?= htmlspecialchars(
                                            $k['email']
                                        ); ?>"
                                    >

                                        <?= htmlspecialchars(
                                            $k['nama']
                                        ); ?>

                                        -
                                        <?= htmlspecialchars(
                                            $k['email']
                                        ); ?>

                                    </option>

                                <?php endwhile; ?>

                            <?php endif; ?>

                        </select>

                    </div>


                    <!-- JUMLAH PAKAI -->

                    <div class="mb-3">

                        <label for="jumlah_pakai">

                            Jumlah Pakai (kWh)

                        </label>

                        <input
                            type="number"
                            name="jumlah_pakai"
                            id="jumlah_pakai"
                            class="form-control"
                            min="1"
                            step="1"
                            placeholder="Contoh: 120"
                            required
                        >

                    </div>


                    <!-- PERIODE -->

                    <div class="mb-3">

                        <label for="periode">
                            Periode
                        </label>

                        <input
                            type="text"
                            name="periode"
                            id="periode"
                            class="form-control"
                            placeholder="Contoh: Januari 2026"
                            required
                        >

                    </div>


                    <!-- DEADLINE -->

                    <div class="mb-3">

                        <label for="deadline">

                            Deadline Pembayaran

                        </label>

                        <input
                            type="date"
                            name="deadline"
                            id="deadline"
                            class="form-control"
                            required
                        >

                    </div>


                    <!-- HARGA -->

                    <div class="mb-3">

                        <label for="harga">

                            Harga

                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input
                                type="number"
                                name="harga"
                                id="harga"
                                class="form-control"
                                readonly
                            >

                        </div>

                        <small class="text-muted">

                            Harga dihitung otomatis:
                            <?= number_format(
                                $hargaPerKwh,
                                0,
                                ',',
                                '.'
                            ); ?>
                            / kWh

                        </small>

                    </div>


                    <!-- PEMBAYARAN -->

                    <div class="mb-3">

                        <label for="pembayaran">

                            Status Pembayaran

                        </label>

                        <select
                            name="pembayaran"
                            id="pembayaran"
                            class="form-select"
                        >

                            <option value="Belum Lunas">
                                Belum Lunas
                            </option>

                            <option value="Lunas">
                                Lunas
                            </option>

                        </select>

                    </div>


                    <!-- BUTTON -->

                    <div class="d-flex gap-2 flex-wrap">

                        <button
                            type="submit"
                            name="simpan"
                            class="btn btn-success"
                        >
                            Simpan
                        </button>


                        <button
                            type="reset"
                            class="btn btn-secondary"
                        >
                            Reset
                        </button>

                    </div>


                </form>

            </div>

        </div>


        <!-- =================================================
             DAFTAR TAGIHAN
             ================================================= -->

        <div class="card shadow tagihan-table-card">


            <div class="card-header bg-primary text-white">

                <strong>
                    Daftar Tagihan
                </strong>

            </div>


            <div class="card-body p-0">


                <?php if ($result->num_rows > 0): ?>


                    <div class="table-responsive">

                        <table
                            class="
                                table
                                table-striped
                                table-bordered
                                align-middle
                                mb-0
                            "
                        >

                            <thead
                                class="table-dark text-center"
                            >

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Konsumen
                                    </th>

                                    <th>
                                        Email
                                    </th>

                                    <th>
                                        Pakai
                                    </th>

                                    <th>
                                        Periode
                                    </th>

                                    <th>
                                        Harga
                                    </th>

                                    <th>
                                        Deadline
                                    </th>

                                    <th>
                                        Pembayaran
                                    </th>

                                    <th>
                                        Aksi
                                    </th>

                                </tr>

                            </thead>


                            <tbody>

                            <?php

                            $no = 1;

                            while (
                                $row = $result->fetch_assoc()
                            ):

                                $isOverdue = false;


                                if (
                                    $row['pembayaran'] === 'Belum Lunas'
                                    &&
                                    !empty($row['deadline'])
                                    &&
                                    $row['deadline'] < $today
                                ) {

                                    $isOverdue = true;

                                }

                            ?>

                                <tr
                                    class="
                                        text-center
                                        <?= $isOverdue
                                            ? 'overdue'
                                            : ''; ?>
                                    "
                                >


                                    <!-- NO -->

                                    <td>
                                        <?= $no++; ?>
                                    </td>


                                    <!-- NAMA -->

                                    <td class="text-start">

                                        <?= htmlspecialchars(
                                            $row['nama']
                                            ?? '-'
                                        ); ?>

                                    </td>


                                    <!-- EMAIL -->

                                    <td class="text-start">

                                        <?= htmlspecialchars(
                                            $row['email']
                                        ); ?>

                                    </td>


                                    <!-- PEMAKAIAN -->

                                    <td>

                                        <?= number_format(
                                            (int) $row['jumlah_pakai'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                        kWh

                                    </td>


                                    <!-- PERIODE -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $row['periode']
                                        ); ?>

                                    </td>


                                    <!-- HARGA -->

                                    <td>

                                        Rp
                                        <?= number_format(
                                            (float) $row['harga'],
                                            0,
                                            ',',
                                            '.'
                                        ); ?>

                                    </td>


                                    <!-- DEADLINE -->

                                    <td>

                                        <?php if (
                                            !empty(
                                                $row['deadline']
                                            )
                                        ): ?>

                                            <?= date(
                                                'd-m-Y',
                                                strtotime(
                                                    $row['deadline']
                                                )
                                            ); ?>

                                        <?php else: ?>

                                            -

                                        <?php endif; ?>

                                    </td>


                                    <!-- STATUS -->

                                    <td>

                                        <?php if (
                                            $row['pembayaran']
                                            === 'Lunas'
                                        ): ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-success
                                                "
                                            >
                                                Lunas
                                            </span>

                                        <?php elseif ($isOverdue): ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-danger
                                                "
                                            >
                                                Terlambat
                                            </span>

                                        <?php else: ?>

                                            <span
                                                class="
                                                    badge
                                                    bg-warning
                                                    text-dark
                                                "
                                            >
                                                Belum Lunas
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- AKSI -->

                                    <td>

                                        <div
                                            class="action-buttons"
                                        >


                                            <!-- UPDATE STATUS -->

                                            <form
                                                method="POST"
                                                class="status-form"
                                            >

                                                <input
                                                    type="hidden"
                                                    name="id_tagih"
                                                    value="<?= $row['id_tagih']; ?>"
                                                >

                                                <select
                                                    name="pembayaran"
                                                    class="form-select form-select-sm"
                                                >

                                                    <option
                                                        value="Belum Lunas"
                                                        <?= $row['pembayaran']
                                                            === 'Belum Lunas'
                                                            ? 'selected'
                                                            : ''; ?>
                                                    >
                                                        Belum Lunas
                                                    </option>

                                                    <option
                                                        value="Lunas"
                                                        <?= $row['pembayaran']
                                                            === 'Lunas'
                                                            ? 'selected'
                                                            : ''; ?>
                                                    >
                                                        Lunas
                                                    </option>

                                                </select>

                                                <button
                                                    type="submit"
                                                    name="update_status"
                                                    class="btn btn-primary btn-sm"
                                                >
                                                    Simpan
                                                </button>

                                            </form>


                                            <!-- HAPUS -->

                                            <a
                                                href="tagihan.php?hapus=<?= $row['id_tagih']; ?>"
                                                class="btn btn-danger btn-sm"
                                                onclick="
                                                    return confirm(
                                                        'Yakin ingin menghapus tagihan ini?'
                                                    );
                                                "
                                            >
                                                Hapus
                                            </a>


                                        </div>

                                    </td>


                                </tr>

                            <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>


                <?php else: ?>


                    <div class="text-center p-5">

                        <h5>
                            Belum Ada Tagihan
                        </h5>

                        <p class="text-muted mb-0">

                            Belum ada data tagihan yang tersimpan.

                        </p>

                    </div>


                <?php endif; ?>


            </div>

        </div>


    </div>

</main>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer class="text-center py-4">

    <small>

        © <?= date('Y'); ?>
        Aplikasi Tagihan Listrik

    </small>

</footer>


<!-- Bootstrap JS -->

<script
    src="bootstrap/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     HITUNG HARGA OTOMATIS
     ===================================================== -->

<script>

    const jumlahPakai =
        document.getElementById('jumlah_pakai');

    const harga =
        document.getElementById('harga');


    const hargaPerKwh =
        <?= (int) $hargaPerKwh; ?>;


    function hitungHarga() {

        const jumlah =
            parseInt(
                jumlahPakai.value
            ) || 0;


        harga.value =
            jumlah * hargaPerKwh;

    }


    jumlahPakai.addEventListener(
        'input',
        hitungHarga
    );

</script>


</body>

</html>