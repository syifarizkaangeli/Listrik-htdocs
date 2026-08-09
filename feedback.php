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
| AMBIL DATA FEEDBACK
|--------------------------------------------------------------------------
|
| feedback.email -> dicocokkan dengan konsumen.email
| konsumen.nama  -> nama konsumen
| feedback.pesan -> isi feedback
|
*/

$query = "
    SELECT
        feedback.id,
        konsumen.nama,
        feedback.email,
        feedback.pesan,
        feedback.waktu
    FROM feedback
    LEFT JOIN konsumen
        ON feedback.email = konsumen.email
    ORDER BY feedback.id DESC
";

$result = $koneksi->query($query);

$error = '';

if (!$result) {
    $error = "Data feedback gagal dimuat: " . $koneksi->error;
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
        Feedback Konsumen - Admin
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
            href="home.php"
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


                <!-- BERANDA -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="home.php"
                    >
                        Beranda
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
                        class="nav-link active"
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


        <!-- PAGE HEADER -->

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h3 class="mb-1">
                    Feedback Konsumen
                </h3>

                <p class="text-muted mb-0">
                    Lihat masukan yang diberikan oleh konsumen.
                </p>

            </div>


            <?php if ($result) : ?>

                <span class="badge bg-primary fs-6">

                    <?= $result->num_rows; ?>

                    Feedback

                </span>

            <?php endif; ?>

        </div>


        <!-- ERROR -->

        <?php if ($error !== '') : ?>

            <div
                class="alert alert-danger"
                role="alert"
            >

                <?= htmlspecialchars($error); ?>

            </div>

        <?php endif; ?>


        <!-- =================================================
             FEEDBACK TABLE
             ================================================= -->

        <div class="card shadow-sm">

            <div class="card-body">


                <div class="table-responsive">

                    <table
                        class="table table-bordered table-striped align-middle mb-0"
                    >

                        <thead class="table-dark text-center">

                            <tr>

                                <th>
                                    ID
                                </th>

                                <th>
                                    Nama Konsumen
                                </th>

                                <th>
                                    Email
                                </th>

                                <th>
                                    Feedback
                                </th>

                                <th>
                                    Waktu
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


                                    <!-- ID -->

                                    <td class="text-center">

                                        <?= htmlspecialchars(
                                            $row['id']
                                        ); ?>

                                    </td>


                                    <!-- NAMA -->

                                    <td>

                                        <?php if (
                                            !empty($row['nama'])
                                        ) : ?>

                                            <?= htmlspecialchars(
                                                $row['nama']
                                            ); ?>

                                        <?php else : ?>

                                            <span class="text-muted">
                                                Konsumen tidak ditemukan
                                            </span>

                                        <?php endif; ?>

                                    </td>


                                    <!-- EMAIL -->

                                    <td>

                                        <?= htmlspecialchars(
                                            $row['email']
                                        ); ?>

                                    </td>


                                    <!-- FEEDBACK -->

                                    <td>

                                        <?= nl2br(
                                            htmlspecialchars(
                                                $row['pesan']
                                            )
                                        ); ?>

                                    </td>


                                    <!-- WAKTU -->

                                    <td class="text-center">

                                        <?= !empty($row['waktu'])
                                            ? htmlspecialchars(
                                                $row['waktu']
                                            )
                                            : '-'; ?>

                                    </td>


                                </tr>

                            <?php endwhile; ?>


                        <?php else : ?>


                            <tr>

                                <td
                                    colspan="5"
                                    class="text-center text-muted py-5"
                                >

                                    <div style="font-size:40px;">
                                        💬
                                    </div>

                                    <div class="mt-2">

                                        Belum ada feedback.

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