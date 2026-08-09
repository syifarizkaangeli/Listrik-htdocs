<?php
session_start();
include 'connect.php';

// Inisialisasi variabel error
$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $pass = $_POST['password'];

    // Menggunakan prepared statement untuk keamanan
    $stmt = $koneksi->prepare("SELECT * FROM admin WHERE username = ? AND pass = ? LIMIT 1");
    $stmt->bind_param("ss", $username, $pass); // "ss" = 2 string
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows == 1) {
        $_SESSION['admin'] = $username;
        header("Location: home.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0">Login Admin</h5>
                    </div>
                    <div class="card-body">
                        <!-- Menampilkan error jika ada -->
                        <?php if ($error) : ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <button type="submit" name="login" class="btn btn-primary w-100">Login</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
