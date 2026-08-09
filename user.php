<?php

session_start();

include 'connect.php';


/*
|--------------------------------------------------------------------------
| CEK JIKA SUDAH LOGIN
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
    header("Location: home_user.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| VARIABLE ERROR
|--------------------------------------------------------------------------
*/

$error = "";


/*
|--------------------------------------------------------------------------
| PROSES LOGIN USER
|--------------------------------------------------------------------------
*/

if (isset($_POST['login'])) {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';


    /*
    |--------------------------------------------------------------------------
    | VALIDASI INPUT
    |--------------------------------------------------------------------------
    */

    if (empty($email) || empty($password)) {

        $error = "Email dan password wajib diisi.";

    } else {

        /*
        |--------------------------------------------------------------------------
        | CARI USER BERDASARKAN EMAIL
        |--------------------------------------------------------------------------
        */

        $stmt = $koneksi->prepare("
            SELECT
                id_cust,
                nama,
                email,
                telp,
                alamat,
                pass
            FROM konsumen
            WHERE email = ?
            LIMIT 1
        ");


        if (!$stmt) {

            $error = "Terjadi kesalahan pada database.";

        } else {

            $stmt->bind_param(
                "s",
                $email
            );

            $stmt->execute();

            $result = $stmt->get_result();


            /*
            |--------------------------------------------------------------------------
            | CEK DATA USER
            |--------------------------------------------------------------------------
            */

            if ($result->num_rows === 1) {

                $user = $result->fetch_assoc();


                /*
                |--------------------------------------------------------------------------
                | CEK PASSWORD
                |--------------------------------------------------------------------------
                |
                | Database kamu saat ini menyimpan password biasa
                | seperti 123456.
                |
                */

                if ($password === $user['pass']) {

                    /*
                    |--------------------------------------------------------------------------
                    | REGENERATE SESSION
                    |--------------------------------------------------------------------------
                    */

                    session_regenerate_id(true);


                    /*
                    |--------------------------------------------------------------------------
                    | SIMPAN SESSION USER
                    |--------------------------------------------------------------------------
                    */

                    $_SESSION['id_cust'] = $user['id_cust'];

                    $_SESSION['nama'] = $user['nama'];

                    $_SESSION['email'] = $user['email'];

                    $_SESSION['telp'] = $user['telp'];

                    $_SESSION['alamat'] = $user['alamat'];


                    /*
                    |--------------------------------------------------------------------------
                    | MASUK KE HOME USER
                    |--------------------------------------------------------------------------
                    */

                    header("Location: home_user.php");
                    exit;

                } else {

                    $error = "Email atau password salah.";

                }

            } else {

                $error = "Email atau password salah.";

            }


            $stmt->close();

        }

    }

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
        Login User - Listrik
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

        .login-wrapper {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 15px;
        }

        .login-card {
            width: 100%;
            max-width: 430px;
            border: none;
            border-radius: 18px;
            overflow: hidden;
        }

        .login-header {
            padding: 25px 20px;
        }

        .login-body {
            padding: 30px;
        }

        .login-icon {
            font-size: 42px;
            margin-bottom: 8px;
        }

        .back-link {
            text-decoration: none;
        }

        @media (max-width: 576px) {

            .login-wrapper {
                padding: 20px 15px;
            }

            .login-body {
                padding: 22px 18px;
            }

            .login-header {
                padding: 20px 15px;
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
            ⚡ Listrik
        </a>

    </div>

</nav>


<!-- =====================================================
     LOGIN
     ===================================================== -->

<div class="login-wrapper">

    <div class="card shadow login-card">


        <!-- HEADER -->

        <div
            class="
                card-header
                bg-primary
                text-white
                text-center
                login-header
            "
        >

            <div class="login-icon">
                👤
            </div>

            <h5 class="mb-1">
                Login User
            </h5>

            <small>
                Masuk untuk melihat tagihan listrik
            </small>

        </div>


        <!-- BODY -->

        <div class="card-body login-body">


            <!-- ERROR -->

            <?php if (!empty($error)): ?>

                <div
                    class="alert alert-danger"
                    role="alert"
                >
                    <?= htmlspecialchars($error); ?>
                </div>

            <?php endif; ?>


            <!-- FORM -->

            <form
                method="POST"
                action="user.php"
            >


                <!-- EMAIL -->

                <div class="mb-3">

                    <label
                        for="email"
                        class="form-label"
                    >
                        Email
                    </label>

                    <input
                        type="email"
                        name="email"
                        id="email"
                        class="form-control"
                        placeholder="Masukkan email"
                        value="<?= htmlspecialchars(
                            $_POST['email'] ?? ''
                        ); ?>"
                        autocomplete="email"
                        required
                    >

                </div>


                <!-- PASSWORD -->

                <div class="mb-3">

                    <label
                        for="password"
                        class="form-label"
                    >
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        id="password"
                        class="form-control"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >

                </div>


                <!-- LOGIN BUTTON -->

                <button
                    type="submit"
                    name="login"
                    class="btn btn-primary w-100"
                >
                    Login
                </button>


            </form>


            <!-- BACK -->

            <div class="text-center mt-3">

                <a
                    href="login.php"
                    class="text-muted back-link"
                >
                    ← Kembali ke pilihan login
                </a>

            </div>


        </div>

    </div>

</div>


<!-- =====================================================
     FOOTER
     ===================================================== -->

<footer class="text-center py-3">

    <small class="text-muted">

        © <?= date('Y'); ?> Aplikasi Listrik

    </small>

</footer>


<!-- Bootstrap JS -->

<script
    src="bootstrap/js/bootstrap.bundle.min.js"
></script>


</body>

</html>