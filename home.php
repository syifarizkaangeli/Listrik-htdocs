<?php

session_start();

include 'connect.php';

/*
|--------------------------------------------------------------------------
| CEK LOGIN ADMIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['admin']) || empty($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| JUMLAH KONSUMEN
|--------------------------------------------------------------------------
*/

$data = [
    'total' => 0
];

$queryKonsumen = "
    SELECT COUNT(*) AS total
    FROM konsumen
";

$resultKonsumen = $koneksi->query($queryKonsumen);

if ($resultKonsumen) {

    $data = $resultKonsumen->fetch_assoc();

}


/*
|--------------------------------------------------------------------------
| HARGA PER KWH
|--------------------------------------------------------------------------
|
| Kalau harga disimpan di tabel pengaturan,
| ambil dari sana.
|
| Jika belum ada tabel pengaturan,
| gunakan nilai default Rp1.500.
|
*/

$hargaPerKwh = 1500;


/*
|--------------------------------------------------------------------------
| JUMLAH KONSUMEN YANG BELUM LUNAS
|--------------------------------------------------------------------------
*/

$data_blm = [
    'total_blm' => 0
];

$queryBelumLunas = "
    SELECT COUNT(DISTINCT email) AS total_blm
    FROM tagihan
    WHERE status != 'Lunas'
";

$resultBelumLunas = $koneksi->query($queryBelumLunas);

if ($resultBelumLunas) {

    $data_blm = $resultBelumLunas->fetch_assoc();

}

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
        Dashboard Admin - Tagihan Listrik
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

    <div class="container">

        <!-- LOGO -->

        <a
            class="navbar-brand"
            href="home.php"
        >
            ⚡ Tagihan Listrik
        </a>


        <!-- MOBILE MENU -->

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


        <!-- MENU -->

        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav mx-auto mb-2 mb-lg-0">


                <!-- DASHBOARD -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="home.php"
                    >
                        Dashboard
                    </a>

                </li>


                <!-- KONSUMEN -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="cust.php"
                    >
                        Konsumen
                    </a>

                </li>


                <!-- TAGIHAN -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="tagihan.php"
                    >
                        Tagihan
                    </a>

                </li>


                <!-- FEEDBACK -->

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
     MAIN CONTENT
     ===================================================== -->

<main>

    <div class="container mt-5">


        <!-- =================================================
             HEADER
             ================================================= -->

        <div class="mb-5">

            <h2 class="fw-bold">
                Dashboard Admin
            </h2>

            <p class="text-muted">
                Selamat datang di sistem manajemen
                Tagihan Listrik.
            </p>

        </div>


        <!-- =================================================
             STATISTICS CARDS
             ================================================= -->

        <div class="row g-4">


            <!-- =============================================
                 CARD 1
                 JUMLAH KONSUMEN
                 ============================================= -->

            <div class="col-md-4">

                <div class="card text-center shadow h-100">

                    <div class="card-body">

                        <div
                            class="mb-3"
                            style="font-size:45px;"
                        >
                            👥
                        </div>


                        <h5 class="card-title">

                            Jumlah Konsumen

                        </h5>


                        <h1 class="text-primary fw-bold">

                            <?= htmlspecialchars(
                                $data['total']
                            ); ?>

                        </h1>


                        <p class="text-muted">

                            Konsumen terdaftar

                        </p>


                        <a
                            href="cust.php"
                            class="btn btn-outline-primary btn-sm"
                        >
                            Kelola Konsumen
                        </a>

                    </div>

                </div>

            </div>


            <!-- =============================================
                 CARD 2
                 HARGA PER KWH
                 ============================================= -->

            <div class="col-md-4">

                <div class="card text-center shadow h-100">

                    <div class="card-body">

                        <div
                            class="mb-3"
                            style="font-size:45px;"
                        >
                            ⚡
                        </div>


                        <h5 class="card-title">

                            Harga per kWh

                        </h5>


                        <h1 class="text-success fw-bold">

                            Rp
                            <?= number_format(
                                $hargaPerKwh,
                                0,
                                ',',
                                '.'
                            ); ?>

                        </h1>


                        <p class="text-muted">

                            Harga listrik per kWh

                        </p>


                        <a
                            href="tagihan.php"
                            class="btn btn-outline-success btn-sm"
                        >
                            Kelola Tagihan
                        </a>

                    </div>

                </div>

            </div>


            <!-- =============================================
                 CARD 3
                 BELUM LUNAS
                 ============================================= -->

            <div class="col-md-4">

                <div class="card text-center shadow h-100">

                    <div class="card-body">

                        <div
                            class="mb-3"
                            style="font-size:45px;"
                        >
                            💳
                        </div>


                        <h5 class="card-title">

                            Belum Lunas

                        </h5>


                        <h1 class="text-danger fw-bold">

                            <?= htmlspecialchars(
                                $data_blm['total_blm']
                            ); ?>

                        </h1>


                        <p class="text-muted">

                            Konsumen yang belum membayar

                        </p>


                        <?php if (
                            $data_blm['total_blm'] > 0
                        ) : ?>

                            <span class="badge bg-danger">

                                Perlu Perhatian

                            </span>

                        <?php else : ?>

                            <span class="badge bg-success">

                                Semua Lunas

                            </span>

                        <?php endif; ?>


                    </div>

                </div>

            </div>


        </div>


        <!-- =================================================
             QUICK MENU
             ================================================= -->

        <div class="mt-5">

            <h4 class="mb-4">

                Menu Cepat

            </h4>


            <div class="row g-3">


                <!-- KONSUMEN -->

                <div class="col-12 col-md-4">

                    <a
                        href="cust.php"
                        class="text-decoration-none"
                    >

                        <div class="card shadow-sm card-hover">

                            <div class="card-body">

                                <h5>

                                    👥 Konsumen

                                </h5>

                                <p class="text-muted mb-0">

                                    Tambah, edit, dan hapus
                                    data konsumen.

                                </p>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- TAGIHAN -->

                <div class="col-12 col-md-4">

                    <a
                        href="tagihan.php"
                        class="text-decoration-none"
                    >

                        <div class="card shadow-sm card-hover">

                            <div class="card-body">

                                <h5>

                                    ⚡ Tagihan

                                </h5>

                                <p class="text-muted mb-0">

                                    Kelola tagihan listrik
                                    konsumen.

                                </p>

                            </div>

                        </div>

                    </a>

                </div>


                <!-- FEEDBACK -->

                <div class="col-12 col-md-4">

                    <a
                        href="feedback.php"
                        class="text-decoration-none"
                    >

                        <div class="card shadow-sm card-hover">

                            <div class="card-body">

                                <h5>

                                    💬 Feedback

                                </h5>

                                <p class="text-muted mb-0">

                                    Lihat kritik dan saran
                                    dari konsumen.

                                </p>

                            </div>

                        </div>

                    </a>

                </div>


            </div>

        </div>


    </div>

</main>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer class="text-center mt-5 py-4">

    <small class="text-muted">

        © <?= date('Y'); ?>
        Aplikasi Tagihan Listrik

    </small>

</footer>


<!-- Bootstrap JS -->

<script
    src="bootstrap/js/bootstrap.bundle.min.js"
></script>


</body>

</html>