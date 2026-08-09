<?php

session_start();

include 'connect.php';

/*
|--------------------------------------------------------------------------
| CEK LOGIN USER
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

$error = '';

/*
|--------------------------------------------------------------------------
| AMBIL DATA KONSUMEN
|--------------------------------------------------------------------------
*/

$nama = 'User';

$stmt = $koneksi->prepare(
    "SELECT nama
     FROM konsumen
     WHERE email = ?
     LIMIT 1"
);

if ($stmt) {

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $resultUser = $stmt->get_result();

    if ($resultUser && $resultUser->num_rows === 1) {

        $dataUser = $resultUser->fetch_assoc();

        $nama = $dataUser['nama'];

    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| HITUNG TOTAL TAGIHAN BELUM DIBAYAR
|--------------------------------------------------------------------------
|
| Sesuaikan status dengan database:
| status = 'Belum Lunas'
|
*/

$totalTagihan = 0;

$stmt = $koneksi->prepare(
    "SELECT COALESCE(SUM(total_tagihan), 0) AS total
     FROM tagihan
     WHERE email = ?
     AND status != 'Lunas'"
);

if ($stmt) {

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $resultTagihan = $stmt->get_result();

    if ($resultTagihan) {

        $dataTagihan = $resultTagihan->fetch_assoc();

        $totalTagihan = (float) ($dataTagihan['total'] ?? 0);

    }

    $stmt->close();

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
        Beranda - Tagihan Listrik
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

        <a
            class="navbar-brand"
            href="home_user.php"
        >
            ⚡ Tagihan Listrik
        </a>


        <!-- MOBILE BUTTON -->

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


                <!-- HOME -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="home_user.php"
                    >
                        Beranda
                    </a>

                </li>


                <!-- TAGIHAN -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="tagihan_user.php"
                    >
                        Tagihan
                    </a>

                </li>


                <!-- FEEDBACK -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="feedback_user.php"
                    >
                        Kritik & Saran
                    </a>

                </li>


                <!-- PROFILE -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="me.php"
                    >
                        Profil
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

    <div class="container mt-5">


        <!-- =================================================
             WELCOME
             ================================================= -->

        <div class="mb-5">

            <h2 class="fw-bold">

                Halo,
                <?= htmlspecialchars($nama); ?> 👋

            </h2>

            <p class="text-muted">

                Selamat datang di aplikasi Tagihan Listrik.

                <br>

                Silakan cek tagihan atau berikan kritik
                dan saran Anda.

            </p>

        </div>


        <!-- =================================================
             CARDS
             ================================================= -->

        <div class="row g-4">


            <!-- =================================================
                 CARD 1 - KRITIK & SARAN
                 ================================================= -->

            <div class="col-md-4">

                <a
                    href="feedback_user.php"
                    class="text-decoration-none text-dark"
                >

                    <div
                        class="card shadow card-hover h-100 border-primary"
                    >

                        <div
                            class="card-body card-body-centered text-center"
                        >

                            <div
                                class="mb-3"
                                style="font-size:45px;"
                            >
                                💬
                            </div>

                            <h5 class="card-title">

                                Isi Kritik & Saran

                            </h5>

                            <p class="text-muted mb-0">

                                Sampaikan pendapat,
                                kritik, atau saran Anda.

                            </p>

                        </div>

                    </div>

                </a>

            </div>


            <!-- =================================================
                 CARD 2 - TAGIHAN
                 ================================================= -->

            <div class="col-md-4">

                <a
                    href="tagihan_user.php"
                    class="text-decoration-none text-dark"
                >

                    <div
                        class="card shadow card-hover h-100 border-danger"
                    >

                        <div
                            class="card-body card-body-centered text-center"
                        >

                            <div
                                class="mb-3"
                                style="font-size:45px;"
                            >
                                ⚡
                            </div>


                            <h5 class="card-title text-danger">

                                Tagihan Belum Dibayar

                            </h5>


                            <h1 class="text-danger fw-bold">

                                Rp
                                <?= number_format(
                                    $totalTagihan,
                                    0,
                                    ',',
                                    '.'
                                ); ?>

                            </h1>


                            <br>


                            <?php if ($totalTagihan > 0) : ?>

                                <span class="badge bg-danger">

                                    Segera Bayar

                                </span>

                            <?php else : ?>

                                <span class="badge bg-success">

                                    Aman

                                </span>

                            <?php endif; ?>


                        </div>

                    </div>

                </a>

            </div>


            <!-- =================================================
                 CARD 3 - PROFIL
                 ================================================= -->

            <div class="col-md-4">

                <a
                    href="me.php"
                    class="text-decoration-none text-dark"
                >

                    <div
                        class="card shadow card-hover h-100 border-success"
                    >

                        <div
                            class="card-body card-body-centered text-center"
                        >

                            <div
                                class="mb-3"
                                style="font-size:45px;"
                            >
                                👤
                            </div>


                            <h5 class="card-title text-success">

                                Profil Saya

                            </h5>


                            <p class="text-muted mb-0">

                                Lihat informasi akun dan
                                data konsumen Anda.

                            </p>

                        </div>

                    </div>

                </a>

            </div>


        </div>


        <!-- =================================================
             INFORMATION
             ================================================= -->

        <div class="card shadow-sm mt-5">

            <div class="card-body">

                <h5 class="mb-3">

                    💡 Informasi

                </h5>

                <p class="text-muted mb-0">

                    Gunakan menu di atas untuk melihat tagihan,
                    memberikan kritik dan saran, atau melihat
                    informasi profil Anda.

                </p>

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