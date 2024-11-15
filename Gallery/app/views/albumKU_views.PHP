<?php

require_once '../../config/database.php'; // Menghubungkan ke database
require_once '../models/Album.php'; // Menghubungkan model Album
require_once '../controllers/C_album.php'; // Menghubungkan controller C_album


// Ambil pesan dari 
// session_start();  // Start session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']); // Hapus pesan setelah ditampilkan

// Periksa apakah user sudah login
if (!isset($_SESSION['UserID'])) {
    header("Location: ../../index.php"); // Arahkan kembali ke halaman login jika belum login
    exit();
}

// Cek jika ada permintaan untuk logout
if (isset($_GET['action']) && $_GET['action'] === 'logout') {

    // Hapus semua data sesi
    session_unset(); 
    session_destroy(); // Hancurkan sesi

    // Redirect ke halaman login
    header("Location: ../../index.php"); 
    exit(); // Pastikan untuk menghentikan eksekusi setelah redirect
}

// Inisialisasi Database dan AlbumController
$db = new Database();
$albumController = new C_album($db);

// Ambil UserID dari session
$userID = $_SESSION['UserID'];

// Ambil semua album berdasarkan UserID
$albums = $albumController->getAllByUser($userID);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Album</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/styleAlbumKU.css"> <!-- Link ke file CSS terpisah -->
    <link rel="stylesheet" href="../../assets/styleNav.css"> <!-- Link ke file CSS terpisah -->
</head>
<body>

    <!-- Include Navbar -->
    <?php include __DIR__ . '/../../assets/navbar/navbar.php'; ?>
    <!-- Container Utama -->
    <div class="container mt-4">
        <h2>Daftar Album Anda</h2>
        
        <!-- ini adalah Pemberitahuan -->
        <?php if (isset($_GET['message'])): ?>
            <div class="alert alert-success">
                <?php echo htmlspecialchars($_GET['message']); ?>
            </div>
            <script>
            // Remove the message parameter from the URL after the page loads
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
            </script>
        <?php endif; ?>

       <!-- Kotak Biru untuk Tambah Album -->
       <div class="d-flex justify-content-center mb-3">
        <a href="./CRUD_album/add_album.php" class="btn btn-primary d-flex align-items-center" style="background-color: #007bff; color: white; padding: 10px 15px; border-radius: 5px; text-decoration: none;">
        <i class="fas fa-plus-circle mr-2"></i> <!-- Ikon tambah -->
        Tambah Album
        </a>
       </div>

        <div class="row mt-4">
            <?php if (isset($albums) && !empty($albums)): ?>
                <?php foreach ($albums as $album): ?>
                    <div class="col-md-4 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($album['NamaAlbum']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($album['Deskripsi']); ?></p>
                                <p class="card-text text-muted">
                                Dibuat pada: <?php echo htmlspecialchars(date("d-m-Y", strtotime($album['TanggalDibuat']))); ?>
                                </p>
                                <form action="../controllers/C_album.php" method="POST" >
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="albumID" value="<?php echo htmlspecialchars($album['AlbumID']); ?>">
                                    <button type="submit" class="btn btn-danger">Hapus</button>
                                </form>
                                 <!-- Edit Album Button -->
                                <form action="./CRUD_album/edit_album.php" method="GET" style="margin-top: 10px;">
                                    <input type="hidden" name="albumID" value="<?php echo htmlspecialchars($album['AlbumID']); ?>">
                                    <button type="submit" class="btn btn-primary">Edit</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="col-12">
                    <div class="alert alert-warning">Tidak ada album yang ditemukan.</div>
                </div>
            <?php endif; ?>
        </div>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- Font Awesome untuk ikon -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
