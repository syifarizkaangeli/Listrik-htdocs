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
| AMBIL DATA USER
|--------------------------------------------------------------------------
*/

$user = null;

$stmt = $koneksi->prepare("
    SELECT
        id_cust,
        nama,
        email,
        telp,
        alamat
    FROM konsumen
    WHERE email = ?
    LIMIT 1
");

if ($stmt) {

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows === 1) {

        $user = $result->fetch_assoc();

    }

    $stmt->close();
}


/*
|--------------------------------------------------------------------------
| USER TIDAK DITEMUKAN
|--------------------------------------------------------------------------
*/

if (!$user) {

    session_destroy();

    header("Location: login.php");
    exit;

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
        Saya - Tagihan Listrik
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

        .profile-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .profile-header {
            text-align: center;
            padding: 30px 20px;
        }

        .profile-icon {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: #198754;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 42px;
            margin: 0 auto 15px;
        }

        .profile-name {
            font-weight: 700;
            margin-bottom: 5px;
        }

        .profile-email {
            color: #6c757d;
            margin-bottom: 0;
        }

        .profile-table th {
            width: 35%;
            white-space: nowrap;
        }

        @media (max-width: 576px) {

            .profile-table th {
                width: 40%;
            }

            .profile-table th,
            .profile-table td {
                font-size: 14px;
                padding: 10px 5px;
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


                <!-- SAYA -->

                <li class="nav-item">

                    <a
                        class="nav-link active"
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
     MAIN CONTENT
     ===================================================== -->

<main>

    <div class="container mt-5 mb-5">

        <div class="row justify-content-center">

            <div class="col-12 col-md-9 col-lg-7">


                <!-- PROFILE CARD -->

                <div class="card shadow profile-card">


                    <!-- HEADER -->

                    <div class="profile-header">

                        <div class="profile-icon">
                            👤
                        </div>


                        <h3 class="profile-name">

                            <?= htmlspecialchars(
                                $user['nama']
                            ); ?>

                        </h3>


                        <p class="profile-email">

                            <?= htmlspecialchars(
                                $user['email']
                            ); ?>

                        </p>

                    </div>


                    <!-- BODY -->

                    <div class="card-body p-4">


                        <h5 class="mb-3">

                            Informasi Saya

                        </h5>


                        <div class="table-responsive">

                            <table
                                class="table table-borderless profile-table"
                            >

                                <tbody>


                                    <!-- NAMA -->

                                    <tr>

                                        <th>
                                            Nama
                                        </th>

                                        <td>

                                            :
                                            <?= htmlspecialchars(
                                                $user['nama']
                                            ); ?>

                                        </td>

                                    </tr>


                                    <!-- EMAIL -->

                                    <tr>

                                        <th>
                                            Email
                                        </th>

                                        <td>

                                            :
                                            <?= htmlspecialchars(
                                                $user['email']
                                            ); ?>

                                        </td>

                                    </tr>


                                    <!-- TELEPON -->

                                    <tr>

                                        <th>
                                            No. Telp
                                        </th>

                                        <td>

                                            :

                                            <?=
                                                !empty(
                                                    $user['telp']
                                                )
                                                ? htmlspecialchars(
                                                    $user['telp']
                                                )
                                                : '-';
                                            ?>

                                        </td>

                                    </tr>


                                    <!-- ALAMAT -->

                                    <tr>

                                        <th>
                                            Alamat
                                        </th>

                                        <td>

                                            :

                                            <?=
                                                !empty(
                                                    $user['alamat']
                                                )
                                                ? nl2br(
                                                    htmlspecialchars(
                                                        $user['alamat']
                                                    )
                                                )
                                                : '-';
                                            ?>

                                        </td>

                                    </tr>


                                </tbody>

                            </table>

                        </div>


                    </div>


                    <!-- FOOTER -->

                    <div class="card-footer text-center bg-white">

                        <a
                            href="home_user.php"
                            class="btn btn-secondary btn-sm"
                        >
                            Kembali
                        </a>

                    </div>


                </div>


            </div>

        </div>

    </div>

</main>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer class="text-center py-4">

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