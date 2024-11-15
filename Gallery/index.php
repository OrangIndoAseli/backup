<?php

include_once './app/controllers/C_login.php';
// Inisialisasi variabel pesan
$message = '';

// Jika form dikirim
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $loginController = new C_login();
    $message = $loginController->loginUser($_POST['username'], $_POST['password']);

    // Jika login berhasil, arahkan ke halaman home
    if ($message === "Login berhasil!") {
        // Menyimpan data user di session
        $_SESSION['UserID'] = $loginController->$user->UserID; // Simpan UserID
        $_SESSION['NamaLengkap'] = $loginController->$user->NamaLengkap; // Simpan Nama Lengkap
        header("Location: /app.views/home_views.php"); // Arahkan ke halaman home
        exit(); // Hentikan eksekusi script setelah pengalihan
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="./assets/styleLogin.css">
</head>
<body>
  <!-- Logo Section -->
  <div class="d-flex justify-content-center py-4">
    <a class="logo d-flex align-items-center w-auto">
      <img src="./assets/icon/photo.png" alt="logo" style="width: 50px; height: auto;">
    </a>
  </div>
  <!-- End Logo Section -->

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card p-4 shadow-sm">
          <h2 class="text-center">LoginMyGallery</h2>
          <form action="index.php" method="post">
            <div class="mb-3">
              <input type="text" name="username" class="form-control" placeholder="Username" required>
            </div>
            <div class="mb-3">
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
          <div class="text-center mt-3">
            <a href="app/views/register_views.php">Don't have an account? Register</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js (for interactivity) -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>

