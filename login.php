<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pilih Login</title>
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <style>
        .card-hover {
            cursor: pointer;
            transition: 0.3s;
        }
        .card-hover:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body class="bg-light">

<div class="container">
    <div class="row justify-content-center align-items-center vh-100">

        <!-- CARD ADMIN -->
        <div class="col-md-4">
            <a href="admin.php" class="text-decoration-none text-dark">
                <div class="card card-hover text-center shadow">
                    <div class="card-body">
                        <h3 class="text-primary">Admin</h3>
                        <p>Login sebagai Administrator</p>
                    </div>
                </div>
            </a>
        </div>

        <!-- CARD USER -->
        <div class="col-md-4">
            <a href="user.php" class="text-decoration-none text-dark">
                <div class="card card-hover text-center shadow">
                    <div class="card-body">
                        <h3 class="text-success">User</h3>
                        <p>Login sebagai User</p>
                    </div>
                </div>
            </a>
        </div>

    </div>
</div>

<script src="bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
