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


/*
|--------------------------------------------------------------------------
| AMBIL DATA TAGIHAN USER
|--------------------------------------------------------------------------
*/

$stmt = $koneksi->prepare("
    SELECT
        id_tagih,
        email,
        jumlah_pakai,
        periode,
        harga,
        deadline,
        pembayaran
    FROM tagihan
    WHERE email = ?
    ORDER BY deadline DESC, id_tagih DESC
");

if (!$stmt) {
    die("Query tagihan gagal: " . $koneksi->error);
}

$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();


/*
|--------------------------------------------------------------------------
| TANGGAL HARI INI
|--------------------------------------------------------------------------
*/

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

    <title>Tagihan Saya</title>


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

        .tagihan-header {
            margin-bottom: 25px;
        }

        .tagihan-header h2 {
            margin-bottom: 5px;
        }

        .tagihan-header p {
            margin-bottom: 0;
            color: #64748b;
        }

        .tagihan-card {
            border: none;
            border-radius: 16px;
            overflow: hidden;
        }

        .tagihan-card .card-header {
            padding: 18px 20px;
        }

        .tagihan-table th,
        .tagihan-table td {
            vertical-align: middle;
        }

        .overdue {
            background-color: #fff5f5 !important;
        }

        .status-badge {
            min-width: 90px;
            display: inline-block;
        }

        .empty-state {
            padding: 35px 20px;
            text-align: center;
        }

        .empty-state-icon {
            font-size: 40px;
            margin-bottom: 10px;
        }

        @media (max-width: 576px) {

            .tagihan-header {
                margin-bottom: 18px;
            }

            .tagihan-header h2 {
                font-size: 22px;
            }

            .tagihan-card .card-header {
                padding: 15px;
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

        <!-- BRAND -->

        <a
            class="navbar-brand"
            href="home_user.php"
        >
            ⚡ Tagihan Listrik
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

                <!-- BERANDA -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="home_user.php"
                    >
                        Beranda
                    </a>

                </li>


                <!-- TAGIHAN -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
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


                <!-- SAYA -->

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="me.php"
                    >
                        Saya
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

        <div class="tagihan-header">

            <h2>
                Tagihan Saya
            </h2>

            <p>
                Berikut daftar tagihan listrik Anda.
            </p>

        </div>


        <!-- =================================================
             TAGIHAN CARD
             ================================================= -->

        <div class="card shadow tagihan-card">


            <div class="card-header bg-primary text-white">

                <strong>
                    Daftar Tagihan
                </strong>

            </div>


            <div class="card-body p-0">


                <?php if ($result->num_rows > 0): ?>


                    <!-- TABLE WRAPPER -->

                    <div class="table-responsive">

                        <table
                            class="table table-striped table-bordered tagihan-table mb-0"
                        >

                            <thead
                                class="table-dark text-center"
                            >

                                <tr>

                                    <th>
                                        No
                                    </th>

                                    <th>
                                        Jumlah Pakai
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

                                </tr>

                            </thead>


                            <tbody>


                            <?php

                            $no = 1;

                            while ($row = $result->fetch_assoc()):

                                /*
                                |--------------------------------------------------------------------------
                                | CEK STATUS TERLAMBAT
                                |--------------------------------------------------------------------------
                                */

                                $isOverdue = false;

                                if (
                                    $row['pembayaran'] === 'Belum Lunas' &&
                                    !empty($row['deadline'])
                                ) {

                                    if (
                                        $row['deadline'] < $today
                                    ) {

                                        $isOverdue = true;

                                    }

                                }

                            ?>


                                <tr
                                    class="
                                        text-center
                                        <?= $isOverdue ? 'overdue' : ''; ?>
                                    "
                                >


                                    <!-- NO -->

                                    <td>

                                        <?= $no++; ?>

                                    </td>


                                    <!-- JUMLAH PAKAI -->

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

                                        <strong>

                                            Rp
                                            <?= number_format(
                                                (float) $row['harga'],
                                                0,
                                                ',',
                                                '.'
                                            ); ?>

                                        </strong>

                                    </td>


                                    <!-- DEADLINE -->

                                    <td>

                                        <?php if (!empty($row['deadline'])): ?>

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


                                    <!-- PEMBAYARAN -->

                                    <td>


                                        <?php if (
                                            $row['pembayaran'] === 'Lunas'
                                        ): ?>


                                            <span
                                                class="
                                                    badge
                                                    bg-success
                                                    status-badge
                                                "
                                            >
                                                Lunas
                                            </span>


                                        <?php elseif ($isOverdue): ?>


                                            <span
                                                class="
                                                    badge
                                                    bg-danger
                                                    status-badge
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
                                                    status-badge
                                                "
                                            >
                                                Belum Lunas
                                            </span>


                                        <?php endif; ?>


                                    </td>


                                </tr>


                            <?php endwhile; ?>


                            </tbody>

                        </table>

                    </div>


                <?php else: ?>


                    <!-- EMPTY STATE -->

                    <div class="empty-state">

                        <div class="empty-state-icon">
                            🧾
                        </div>

                        <h5>
                            Belum Ada Tagihan
                        </h5>

                        <p class="text-muted mb-0">

                            Belum ada tagihan listrik untuk akun Anda.

                        </p>

                    </div>


                <?php endif; ?>


            </div>


            <!-- CARD FOOTER -->

            <div class="card-footer bg-white text-center">

                <a
                    href="home_user.php"
                    class="btn btn-secondary btn-sm"
                >
                    Kembali
                </a>

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


</body>

</html>


<?php

$stmt->close();

?>