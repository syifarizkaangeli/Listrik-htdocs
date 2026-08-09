<?php

session_start();

include 'connect.php';

/*
|--------------------------------------------------------------------------
| Cek Login User
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['email']) || empty($_SESSION['email'])) {
    header("Location: login.php");
    exit;
}

$email = $_SESSION['email'];

$error = '';
$success = '';


/*
|--------------------------------------------------------------------------
| Proses Kirim Feedback
|--------------------------------------------------------------------------
*/

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {

    $pesan = trim($_POST['pesan'] ?? '');

    if ($pesan === '') {

        $error = "Pesan tidak boleh kosong.";

    } elseif (strlen($pesan) < 3) {

        $error = "Pesan terlalu pendek.";

    } else {

        $stmt = $koneksi->prepare(
            "INSERT INTO feedback (email, pesan)
             VALUES (?, ?)"
        );

        if ($stmt) {

            $stmt->bind_param(
                "ss",
                $email,
                $pesan
            );

            if ($stmt->execute()) {

                $stmt->close();

                header("Location: feedback.php?status=success");
                exit;

            } else {

                $error = "Feedback gagal dikirim.";

            }

            $stmt->close();

        } else {

            $error = "Terjadi kesalahan pada database.";

        }
    }
}


/*
|--------------------------------------------------------------------------
| Pesan Berhasil
|--------------------------------------------------------------------------
*/

if (
    isset($_GET['status']) &&
    $_GET['status'] === 'success'
) {

    $success = "Feedback berhasil dikirim. Terima kasih!";

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
        Feedback - Tagihan Listrik
    </title>

    <link
        rel="stylesheet"
        href="bootstrap/css/bootstrap.min.css"
    >

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


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarMenu"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbarMenu"
        >

            <ul class="navbar-nav mx-auto">

                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="home_user.php"
                    >
                        Beranda
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="tagihan_user.php"
                    >
                        Tagihan
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link active"
                        href="feedback.php"
                    >
                        Feedback
                    </a>

                </li>


                <li class="nav-item">

                    <a
                        class="nav-link"
                        href="me.php"
                    >
                        Profil
                    </a>

                </li>

            </ul>


            <a
                href="logout.php"
                class="btn btn-outline-light btn-sm"
            >
                Logout
            </a>

        </div>

    </div>

</nav>


<!-- =====================================================
     CONTENT
     ===================================================== -->

<main>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-lg-7 col-md-9">


                <!-- TITLE -->

                <div class="text-center mb-4">

                    <h2>
                        Feedback
                    </h2>

                    <p class="text-muted">
                        Berikan kritik dan saran untuk kami.
                    </p>

                </div>


                <!-- SUCCESS -->

                <?php if ($success !== '') : ?>

                    <div
                        class="alert alert-success"
                        role="alert"
                    >

                        <?= htmlspecialchars($success); ?>

                    </div>

                <?php endif; ?>


                <!-- ERROR -->

                <?php if ($error !== '') : ?>

                    <div
                        class="alert alert-danger"
                        role="alert"
                    >

                        <?= htmlspecialchars($error); ?>

                    </div>

                <?php endif; ?>


                <!-- FEEDBACK CARD -->

                <div class="card shadow-sm">

                    <div class="card-body p-4">


                        <form
                            method="POST"
                            action="feedback.php"
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
                                    id="email"
                                    class="form-control"
                                    value="<?= htmlspecialchars($email); ?>"
                                    readonly
                                >

                            </div>


                            <!-- PESAN -->

                            <div class="mb-3">

                                <label
                                    for="pesan"
                                    class="form-label"
                                >
                                    Pesan
                                </label>

                                <textarea
                                    name="pesan"
                                    id="pesan"
                                    class="form-control"
                                    rows="5"
                                    placeholder="Ketik pesan Anda..."
                                    required
                                ></textarea>

                            </div>


                            <!-- BUTTON -->

                            <button
                                type="submit"
                                name="submit"
                                class="btn btn-primary w-100"
                            >
                                Kirim
                            </button>


                        </form>


                    </div>

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

        © <?= date('Y'); ?> Aplikasi Tagihan Listrik

    </small>

</footer>


<script
    src="bootstrap/js/bootstrap.bundle.min.js"
></script>

</body>

</html>