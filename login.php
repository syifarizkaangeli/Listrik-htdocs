<?php
session_start();

/*
|--------------------------------------------------------------------------
| JIKA SUDAH LOGIN
|--------------------------------------------------------------------------
|
| Kalau admin sudah login -> langsung ke home.php
| Kalau user sudah login  -> langsung ke home_user.php
|
*/

if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
    header("Location: home.php");
    exit;
}

if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    header("Location: home_user.php");
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
        Login - Tagihan Listrik
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

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 15px;
        }

        .login-container {
            width: 100%;
            max-width: 850px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .login-header .icon {
            font-size: 60px;
            margin-bottom: 10px;
        }

        .login-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #6c757d;
            margin-bottom: 0;
        }

        .login-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            transition: all 0.25s ease;
        }

        .login-card:hover {
            transform: translateY(-5px);
        }

        .login-card .card-body {
            padding: 35px 25px;
        }

        .login-card h3 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .login-card p {
            color: #6c757d;
        }

        .login-icon {
            font-size: 50px;
            margin-bottom: 15px;
        }

        .login-button {
            margin-top: 15px;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
        }

        footer {
            margin-top: auto;
        }

        @media (max-width: 767px) {

            main {
                padding: 30px 15px;
            }

            .login-header {
                margin-bottom: 25px;
            }

            .login-header .icon {
                font-size: 50px;
            }

            .login-header h1 {
                font-size: 28px;
            }

            .login-card .card-body {
                padding: 30px 20px;
            }

        }

    </style>

</head>


<body>


<!-- =====================================================
     NAVBAR
     ===================================================== -->

<nav class="navbar navbar-dark">

    <div class="container">

        <a
            class="navbar-brand"
            href="login.php"
        >
            ⚡ Tagihan Listrik
        </a>

    </div>

</nav>


<!-- =====================================================
     MAIN CONTENT
     ===================================================== -->

<main>

    <div class="login-container">


        <!-- HEADER -->

        <div class="login-header">

            <div class="icon">
                ⚡
            </div>

            <h1>
                Selamat Datang
            </h1>

            <p>
                Silakan pilih jenis akun untuk masuk
            </p>

        </div>


        <!-- LOGIN CARDS -->

        <div class="row g-4 justify-content-center">


            <!-- =================================================
                 CARD ADMIN
                 ================================================= -->

            <div class="col-12 col-md-6">

                <a
                    href="admin.php"
                    class="text-decoration-none text-dark"
                >

                    <div
                        class="card login-card card-hover shadow h-100"
                    >

                        <div
                            class="card-body text-center"
                        >

                            <div class="login-icon">
                                🛡️
                            </div>


                            <h3 class="text-primary">

                                Admin

                            </h3>


                            <p>

                                Login sebagai Administrator

                            </p>


                            <span
                                class="btn btn-primary login-button"
                            >

                                Login Admin

                            </span>

                        </div>

                    </div>

                </a>

            </div>


            <!-- =================================================
                 CARD USER
                 ================================================= -->

            <div class="col-12 col-md-6">

                <a
                    href="user.php"
                    class="text-decoration-none text-dark"
                >

                    <div
                        class="card login-card card-hover shadow h-100"
                    >

                        <div
                            class="card-body text-center"
                        >

                            <div class="login-icon">
                                👤
                            </div>


                            <h3 class="text-success">

                                User

                            </h3>


                            <p>

                                Login sebagai User

                            </p>


                            <span
                                class="btn btn-success login-button"
                            >

                                Login User

                            </span>

                        </div>

                    </div>

                </a>

            </div>


        </div>


        <!-- INFO -->

        <div class="text-center mt-4">

            <small class="text-muted">

                Pilih akun sesuai dengan akses Anda.

            </small>

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