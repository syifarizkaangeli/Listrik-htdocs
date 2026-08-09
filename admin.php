<?php

session_start();

include 'connect.php';

$error = '';

/*
|--------------------------------------------------------------------------
| Jika admin sudah login
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['admin']) && !empty($_SESSION['admin'])) {
    header("Location: home.php");
    exit;
}


/*
|--------------------------------------------------------------------------
| Proses Login
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    /*
    | Validasi input
    */

    if ($username === '' || $password === '') {

        $error = "Username dan password wajib diisi.";

    } else {

        /*
        | Prepared statement
        | Tetap menggunakan tabel admin yang sudah ada
        */

        $stmt = $koneksi->prepare(
            "SELECT username, pass
             FROM admin
             WHERE username = ?
             LIMIT 1"
        );

        if ($stmt) {

            $stmt->bind_param("s", $username);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result && $result->num_rows === 1) {

                $admin = $result->fetch_assoc();

                /*
                | Database project saat ini menggunakan
                | password biasa pada kolom `pass`.
                |
                | Jadi kita pertahankan agar tidak merusak
                | database yang sudah ada.
                */

                if ($password === $admin['pass']) {

                    session_regenerate_id(true);

                    $_SESSION['admin'] = $admin['username'];

                    header("Location: home.php");
                    exit;

                } else {

                    $error = "Username atau password salah.";

                }

            } else {

                $error = "Username atau password salah.";

            }

            $stmt->close();

        } else {

            $error = "Terjadi kesalahan pada koneksi database.";

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

    <title>Login Admin - Aplikasi Tagihan Listrik</title>

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


<body class="bg-light">


<!-- =====================================================
     LOGIN CONTAINER
     ===================================================== -->

<div class="login-container">

    <div class="login-card">

        <!-- Logo / Icon -->
        <div class="text-center mb-4">

            <div
                style="
                    width:70px;
                    height:70px;
                    margin:0 auto 15px;
                    border-radius:50%;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                    background:linear-gradient(135deg,#1d4ed8,#2563eb);
                    color:white;
                    font-size:30px;
                    box-shadow:0 8px 20px rgba(37,99,235,.20);
                "
            >
                ⚡
            </div>

            <h3 class="mb-1">
                Login Admin
            </h3>

            <p class="text-muted mb-0">
                Aplikasi Tagihan Listrik
            </p>

        </div>


        <!-- =================================================
             ERROR MESSAGE
             ================================================= -->

        <?php if (!empty($error)) : ?>

            <div
                class="alert alert-danger"
                role="alert"
            >
                <?= htmlspecialchars($error); ?>
            </div>

        <?php endif; ?>


        <!-- =================================================
             LOGIN FORM
             ================================================= -->

        <form
            method="POST"
            action=""
            autocomplete="off"
        >

            <!-- Username -->

            <div class="mb-3">

                <label
                    for="username"
                    class="form-label"
                >
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    placeholder="Masukkan username"
                    autocomplete="username"
                    value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>"
                    required
                    autofocus
                >

            </div>


            <!-- Password -->

            <div class="mb-3">

                <label
                    for="password"
                    class="form-label"
                >
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    placeholder="Masukkan password"
                    autocomplete="current-password"
                    required
                >

            </div>


            <!-- Login Button -->

            <button
                type="submit"
                name="login"
                class="btn btn-primary w-100"
            >
                Login
            </button>

        </form>


        <!-- =================================================
             INFO
             ================================================= -->

        <div class="text-center mt-4">

            <small class="text-muted">
                Halaman khusus administrator
            </small>

        </div>

    </div>

</div>


<!-- Bootstrap JS -->

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>